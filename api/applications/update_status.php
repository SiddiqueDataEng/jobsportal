<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

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

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->application_id) && !empty($data->status)) {
    
    $query = "UPDATE job_applications 
              SET status = :status, 
                  updated_at = CURRENT_TIMESTAMP 
              WHERE id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":status", $data->status);
    $stmt->bindParam(":id", $data->application_id);
    
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'message' => 'Application status updated successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to update status']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Incomplete data']);
}
