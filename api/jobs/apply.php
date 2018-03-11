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

if (!empty($data->job_posting_id)) {
    
    // Check if job exists and is active
    $jobQuery = "SELECT * FROM job_postings WHERE id = :job_id AND status = 'Active'";
    $jobStmt = $db->prepare($jobQuery);
    $jobStmt->bindParam(":job_id", $data->job_posting_id);
    $jobStmt->execute();
    
    if ($jobStmt->rowCount() == 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Job not found or no longer active']);
        exit();
    }
    
    $job = $jobStmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if already applied
    $checkQuery = "SELECT id FROM job_applications WHERE user_id = :user_id AND job_posting_id = :job_id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(":user_id", $user['user_id']);
    $checkStmt->bindParam(":job_id", $data->job_posting_id);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'You have already applied to this job']);
        exit();
    }
    
    // Create application
    $query = "INSERT INTO job_applications 
              (user_id, job_posting_id, company_name, position, job_link, date_applied, 
               contact_person, contact_email, contact_phone, status, notes) 
              VALUES (:user_id, :job_posting_id, :company_name, :position, :job_link, 
                      CURDATE(), :contact_person, :contact_email, :contact_phone, 'Applied', :notes)";
    
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":user_id", $user['user_id']);
    $stmt->bindParam(":job_posting_id", $data->job_posting_id);
    $stmt->bindParam(":company_name", $job['company_name']);
    $stmt->bindParam(":position", $job['position']);
    $stmt->bindParam(":job_link", $job['job_link']);
    $stmt->bindParam(":contact_person", $job['contact_person']);
    $stmt->bindParam(":contact_email", $job['contact_email']);
    $stmt->bindParam(":contact_phone", $job['contact_phone']);
    $stmt->bindParam(":notes", $data->notes);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            'success' => true, 
            'message' => 'Application submitted successfully',
            'application_id' => $db->lastInsertId()
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to submit application']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Job ID required']);
}
