<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

// Verify admin access using role column
if (!isset($user['role']) || $user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Admin access required. Only admins can delete jobs.']);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $query = "DELETE FROM job_postings WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Job deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Job not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Job ID required']);
}
