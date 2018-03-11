<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

$database = new Database();
$db = $database->getConnection();

$status = isset($_GET['status']) ? $_GET['status'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

$query = "SELECT * FROM job_applications WHERE user_id = :user_id";

if ($status) {
    $query .= " AND status = :status";
}

if ($search) {
    $query .= " AND (company_name LIKE :search OR position LIKE :search)";
}

$query .= " ORDER BY date_applied DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(":user_id", $user['user_id']);

if ($status) {
    $stmt->bindParam(":status", $status);
}

if ($search) {
    $searchParam = "%{$search}%";
    $stmt->bindParam(":search", $searchParam);
}

$stmt->execute();

$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode([
    'success' => true,
    'data' => $applications
]);
