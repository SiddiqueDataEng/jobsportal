<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

// Verify admin access
if (!isset($user['role']) || $user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Admin access required']);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$job_id = isset($_GET['job_id']) ? $_GET['job_id'] : null;

if (!$job_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Job ID required']);
    exit();
}

// Get job details
$jobQuery = "SELECT * FROM job_postings WHERE id = :job_id";
$jobStmt = $db->prepare($jobQuery);
$jobStmt->bindParam(":job_id", $job_id);
$jobStmt->execute();

if ($jobStmt->rowCount() == 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Job not found']);
    exit();
}

$job = $jobStmt->fetch(PDO::FETCH_ASSOC);

// Get all applicants for this job
$query = "SELECT ja.*, u.full_name, u.email, u.phone 
          FROM job_applications ja 
          LEFT JOIN users u ON ja.user_id = u.id 
          WHERE ja.job_posting_id = :job_id 
          ORDER BY ja.date_applied DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(":job_id", $job_id);
$stmt->execute();

$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get status breakdown
$statusQuery = "SELECT status, COUNT(*) as count 
                FROM job_applications 
                WHERE job_posting_id = :job_id 
                GROUP BY status";

$statusStmt = $db->prepare($statusQuery);
$statusStmt->bindParam(":job_id", $job_id);
$statusStmt->execute();

$statusBreakdown = $statusStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate statistics
$stats = [
    'total' => count($applicants),
    'applied' => 0,
    'shortlisted' => 0,
    'screening' => 0,
    'interview' => 0,
    'offer' => 0,
    'rejected' => 0
];

foreach ($statusBreakdown as $status) {
    $statusLower = strtolower($status['status']);
    if (isset($stats[$statusLower])) {
        $stats[$statusLower] = (int)$status['count'];
    }
}

http_response_code(200);
echo json_encode([
    'success' => true,
    'job' => $job,
    'applicants' => $applicants,
    'stats' => $stats,
    'statusBreakdown' => $statusBreakdown
]);
