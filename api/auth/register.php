<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password) && !empty($data->full_name)) {
    
    // Check if email already exists
    $query = "SELECT id FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $data->email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit();
    }
    
    $query = "INSERT INTO users (full_name, email, password, phone, career_preferences) 
              VALUES (:full_name, :email, :password, :phone, :career_preferences)";
    
    $stmt = $db->prepare($query);
    
    $hashed_password = password_hash($data->password, PASSWORD_BCRYPT);
    
    $stmt->bindParam(":full_name", $data->full_name);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":password", $hashed_password);
    $stmt->bindParam(":phone", $data->phone);
    $stmt->bindParam(":career_preferences", $data->career_preferences);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'User registered successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Unable to register user']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Incomplete data']);
}
