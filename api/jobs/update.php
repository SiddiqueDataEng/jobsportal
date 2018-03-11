<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

// Verify admin access using role column
if (!isset($user['role']) || $user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Admin access required. Only admins can update jobs.']);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    
    $query = "UPDATE job_postings SET 
              company_name = :company_name,
              position = :position,
              job_link = :job_link,
              description = :description,
              requirements = :requirements,
              salary_range = :salary_range,
              location = :location,
              job_type = :job_type,
              contact_person = :contact_person,
              contact_email = :contact_email,
              contact_phone = :contact_phone,
              status = :status,
              deadline = :deadline
              WHERE id = :id";
    
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":id", $data->id);
    $stmt->bindParam(":company_name", $data->company_name);
    $stmt->bindParam(":position", $data->position);
    $stmt->bindParam(":job_link", $data->job_link);
    $stmt->bindParam(":description", $data->description);
    $stmt->bindParam(":requirements", $data->requirements);
    $stmt->bindParam(":salary_range", $data->salary_range);
    $stmt->bindParam(":location", $data->location);
    $stmt->bindParam(":job_type", $data->job_type);
    $stmt->bindParam(":contact_person", $data->contact_person);
    $stmt->bindParam(":contact_email", $data->contact_email);
    $stmt->bindParam(":contact_phone", $data->contact_phone);
    $stmt->bindParam(":status", $data->status);
    $stmt->bindParam(":deadline", $data->deadline);
    
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Job updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to update job']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Job ID required']);
}
