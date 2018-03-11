<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $query = "DELETE FROM job_applications WHERE id = :id AND user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":user_id", $user['user_id']);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Application deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Application not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Application ID required']);
}
