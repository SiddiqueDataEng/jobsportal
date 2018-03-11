<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $query = "SELECT * FROM job_applications WHERE id = :id AND user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":user_id", $user['user_id']);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $application = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get documents
        $docQuery = "SELECT * FROM documents WHERE application_id = :app_id";
        $docStmt = $db->prepare($docQuery);
        $docStmt->bindParam(":app_id", $id);
        $docStmt->execute();
        $application['documents'] = $docStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get reminders
        $remQuery = "SELECT * FROM reminders WHERE application_id = :app_id ORDER BY reminder_date";
        $remStmt = $db->prepare($remQuery);
        $remStmt->bindParam(":app_id", $id);
        $remStmt->execute();
        $application['reminders'] = $remStmt->fetchAll(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $application]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Application not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Application ID required']);
}
