<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$status = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;
$job_type = isset($_GET['job_type']) ? $_GET['job_type'] : null;
$location = isset($_GET['location']) ? $_GET['location'] : null;

$query = "SELECT jp.*, u.full_name as posted_by_name,
          (SELECT COUNT(*) FROM job_applications WHERE job_posting_id = jp.id) as application_count
          FROM job_postings jp 
          LEFT JOIN users u ON jp.posted_by = u.id";

// Only filter by status if provided
if ($status !== null) {
    $query .= " WHERE jp.status = :status";
    $hasWhere = true;
} else {
    $hasWhere = false;
}

if ($search) {
    $query .= $hasWhere ? " AND" : " WHERE";
    $query .= " (jp.company_name LIKE :search OR jp.position LIKE :search OR jp.description LIKE :search)";
    $hasWhere = true;
}

if ($job_type) {
    $query .= $hasWhere ? " AND" : " WHERE";
    $query .= " jp.job_type = :job_type";
    $hasWhere = true;
}

if ($location) {
    $query .= $hasWhere ? " AND" : " WHERE";
    $query .= " jp.location LIKE :location";
}

$query .= " ORDER BY jp.posted_date DESC";

$stmt = $db->prepare($query);

if ($status !== null) {
    $stmt->bindParam(":status", $status);
}

if ($search) {
    $searchParam = "%{$search}%";
    $stmt->bindParam(":search", $searchParam);
}

if ($job_type) {
    $stmt->bindParam(":job_type", $job_type);
}

if ($location) {
    $locationParam = "%{$location}%";
    $stmt->bindParam(":location", $locationParam);
}

$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode([
    'success' => true,
    'data' => $jobs
]);
