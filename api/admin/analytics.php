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

// Applications by company
$stmt = $db->query("SELECT company_name, COUNT(*) as count 
                    FROM job_applications 
                    GROUP BY company_name 
                    ORDER BY count DESC 
                    LIMIT 10");
$byCompany = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Applications by user
$stmt = $db->query("SELECT u.full_name, COUNT(ja.id) as count 
                    FROM users u 
                    LEFT JOIN job_applications ja ON u.id = ja.user_id 
                    GROUP BY u.id, u.full_name 
                    HAVING count > 0
                    ORDER BY count DESC 
                    LIMIT 10");
$byUser = $stmt->fetchAll(PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode([
    'success' => true,
    'data' => [
        'byCompany' => $byCompany,
        'byUser' => $byUser
    ]
]);
