<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../../config/database.php';
require_once '../../includes/jwt_helper.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    
    $query = "SELECT id, full_name, email, password, role FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $data->email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($data->password, $row['password'])) {
            $token = JWTHelper::encode([
                'user_id' => $row['id'],
                'email' => $row['email'],
                'full_name' => $row['full_name'],
                'role' => $row['role'] ?? 'job_seeker'
            ]);
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $row['id'],
                    'full_name' => $row['full_name'],
                    'email' => $row['email'],
                    'role' => $row['role'] ?? 'job_seeker'
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Incomplete data']);
}
