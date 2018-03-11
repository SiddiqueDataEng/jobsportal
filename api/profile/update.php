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

$query = "UPDATE users SET 
          full_name = :full_name,
          phone = :phone,
          career_preferences = :career_preferences
          WHERE id = :user_id";

$stmt = $db->prepare($query);

$stmt->bindParam(":user_id", $user['user_id']);
$stmt->bindParam(":full_name", $data->full_name);
$stmt->bindParam(":phone", $data->phone);
$stmt->bindParam(":career_preferences", $data->career_preferences);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Unable to update profile']);
}
