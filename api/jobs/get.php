<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $query = "SELECT jp.*, u.full_name as posted_by_name,
              (SELECT COUNT(*) FROM job_applications WHERE job_posting_id = jp.id) as application_count
              FROM job_postings jp 
              LEFT JOIN users u ON jp.posted_by = u.id
              WHERE jp.id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $job = $stmt->fetch(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $job]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Job not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Job ID required']);
}
