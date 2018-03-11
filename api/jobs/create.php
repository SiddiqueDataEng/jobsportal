<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

// Verify admin access using role column
if (!isset($user['role']) || $user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Admin access required. Only admins can post jobs.']);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->company_name) && !empty($data->position) && !empty($data->posted_date)) {
    
    $query = "INSERT INTO job_postings 
              (company_name, position, job_link, description, requirements, salary_range, 
               location, job_type, contact_person, contact_email, contact_phone, 
               posted_by, status, posted_date, deadline) 
              VALUES (:company_name, :position, :job_link, :description, :requirements, 
                      :salary_range, :location, :job_type, :contact_person, :contact_email, 
                      :contact_phone, :posted_by, :status, :posted_date, :deadline)";
    
    $stmt = $db->prepare($query);
    
    $status = !empty($data->status) ? $data->status : 'Active';
    
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
    $stmt->bindParam(":posted_by", $user['user_id']);
    $stmt->bindParam(":status", $status);
    $stmt->bindParam(":posted_date", $data->posted_date);
    $stmt->bindParam(":deadline", $data->deadline);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            'success' => true, 
            'message' => 'Job posted successfully',
            'job_id' => $db->lastInsertId()
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to post job']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Incomplete data']);
}
