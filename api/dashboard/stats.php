<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

$database = new Database();
$db = $database->getConnection();

// Get status counts
$query = "SELECT status, COUNT(*) as count FROM job_applications 
          WHERE user_id = :user_id GROUP BY status";
$stmt = $db->prepare($query);
$stmt->bindParam(":user_id", $user['user_id']);
$stmt->execute();
$statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total applications
$query = "SELECT COUNT(*) as total FROM job_applications WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(":user_id", $user['user_id']);
$stmt->execute();
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get recent applications
$query = "SELECT * FROM job_applications WHERE user_id = :user_id 
          ORDER BY date_applied DESC LIMIT 5";
$stmt = $db->prepare($query);
$stmt->bindParam(":user_id", $user['user_id']);
$stmt->execute();
$recentApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode([
    'success' => true,
    'data' => [
        'total' => $total,
        'statusCounts' => $statusCounts,
        'recentApplications' => $recentApplications
    ]
]);
