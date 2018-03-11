<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->application_id) && !empty($data->reminder_type) && !empty($data->reminder_date)) {
    
    // Verify application belongs to user
    $query = "SELECT id FROM job_applications WHERE id = :app_id AND user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":app_id", $data->application_id);
    $stmt->bindParam(":user_id", $user['user_id']);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }
    
    $query = "INSERT INTO reminders (application_id, reminder_type, reminder_date, description) 
              VALUES (:application_id, :reminder_type, :reminder_date, :description)";
    
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":application_id", $data->application_id);
    $stmt->bindParam(":reminder_type", $data->reminder_type);
    $stmt->bindParam(":reminder_date", $data->reminder_date);
    $stmt->bindParam(":description", $data->description);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Reminder created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to create reminder']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Incomplete data']);
}
