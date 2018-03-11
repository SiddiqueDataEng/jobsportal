<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    
    $query = "UPDATE job_applications SET 
              company_name = :company_name,
              position = :position,
              job_link = :job_link,
              date_applied = :date_applied,
              contact_person = :contact_person,
              contact_email = :contact_email,
              contact_phone = :contact_phone,
              status = :status,
              notes = :notes
              WHERE id = :id AND user_id = :user_id";
    
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":id", $data->id);
    $stmt->bindParam(":user_id", $user['user_id']);
    $stmt->bindParam(":company_name", $data->company_name);
    $stmt->bindParam(":position", $data->position);
    $stmt->bindParam(":job_link", $data->job_link);
    $stmt->bindParam(":date_applied", $data->date_applied);
    $stmt->bindParam(":contact_person", $data->contact_person);
    $stmt->bindParam(":contact_email", $data->contact_email);
    $stmt->bindParam(":contact_phone", $data->contact_phone);
    $stmt->bindParam(":status", $data->status);
    $stmt->bindParam(":notes", $data->notes);
    
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Application updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to update application']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Application ID required']);
}
