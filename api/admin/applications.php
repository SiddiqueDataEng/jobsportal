<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

// Verify admin access using role column
if (!isset($user['role']) || $user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Admin access required']);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$status = isset($_GET['status']) ? $_GET['status'] : null;

$query = "SELECT ja.*, u.full_name as user_name, u.email as user_email 
          FROM job_applications ja 
          JOIN users u ON ja.user_id = u.id";

if ($status) {
    $query .= " WHERE ja.status = :status";
}

$query .= " ORDER BY ja.date_applied DESC";

$stmt = $db->prepare($query);

if ($status) {
    $stmt->bindParam(":status", $status);
}

$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode([
    'success' => true,
    'data' => $applications
]);
