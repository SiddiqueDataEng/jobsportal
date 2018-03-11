<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../config/config.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

$database = new Database();
$db = $database->getConnection();

if (!isset($_FILES['document']) || !isset($_POST['application_id']) || !isset($_POST['document_type'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$applicationId = $_POST['application_id'];
$documentType = $_POST['document_type'];

// Verify application belongs to user
$query = "SELECT id FROM job_applications WHERE id = :app_id AND user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(":app_id", $applicationId);
$stmt->bindParam(":user_id", $user['user_id']);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$file = $_FILES['document'];
$fileSize = $file['size'];
$fileName = basename($file['name']);
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if ($fileSize > MAX_FILE_SIZE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'File too large']);
    exit();
}

if (!in_array($fileExt, ALLOWED_EXTENSIONS)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    exit();
}

$uploadDir = UPLOAD_DIR . $user['user_id'] . '/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$newFileName = uniqid() . '_' . $fileName;
$filePath = $uploadDir . $newFileName;

if (move_uploaded_file($file['tmp_name'], $filePath)) {
    
    $query = "INSERT INTO documents (application_id, document_type, file_name, file_path, file_size) 
              VALUES (:application_id, :document_type, :file_name, :file_path, :file_size)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":application_id", $applicationId);
    $stmt->bindParam(":document_type", $documentType);
    $stmt->bindParam(":file_name", $fileName);
    $stmt->bindParam(":file_path", $filePath);
    $stmt->bindParam(":file_size", $fileSize);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Document uploaded successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Upload failed']);
}
