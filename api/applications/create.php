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

if (!empty($data->company_name) && !empty($data->position) && !empty($data->date_applied)) {
    
    $query = "INSERT INTO job_applications 
              (user_id, company_name, position, job_link, date_applied, contact_person, 
               contact_email, contact_phone, status, notes) 
              VALUES (:user_id, :company_name, :position, :job_link, :date_applied, 
                      :contact_person, :contact_email, :contact_phone, :status, :notes)";
    
    $stmt = $db->prepare($query);
    
    $status = !empty($data->status) ? $data->status : 'Applied';
    
    $stmt->bindParam(":user_id", $user['user_id']);
    $stmt->bindParam(":company_name", $data->company_name);
    $stmt->bindParam(":position", $data->position);
    $stmt->bindParam(":job_link", $data->job_link);
    $stmt->bindParam(":date_applied", $data->date_applied);
    $stmt->bindParam(":contact_person", $data->contact_person);
    $stmt->bindParam(":contact_email", $data->contact_email);
    $stmt->bindParam(":contact_phone", $data->contact_phone);
    $stmt->bindParam(":status", $status);
    $stmt->bindParam(":notes", $data->notes);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            'success' => true, 
            'message' => 'Application created successfully',
            'application_id' => $db->lastInsertId()
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to create application']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Incomplete data']);
}
