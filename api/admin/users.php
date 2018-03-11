<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';
require_once '../../includes/auth_middleware.php';

$user = authenticate();

// Verify admin access using role column
if (!isset($user['role']) || $user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Admin access required']);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : null;

$query = "SELECT u.*, COUNT(ja.id) as application_count 
          FROM users u 
          LEFT JOIN job_applications ja ON u.id = ja.user_id 
          GROUP BY u.id 
          ORDER BY u.created_at DESC";

if ($limit) {
    $query .= " LIMIT $limit";
}

$stmt = $db->query($query);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode([
    'success' => true,
    'data' => $users
]);
