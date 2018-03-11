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

// Total users
$totalUsers = $db->query('SELECT COUNT(*) FROM users')->fetchColumn();

// Total applications
$totalApplications = $db->query('SELECT COUNT(*) FROM job_applications')->fetchColumn();

// Active today (applications created today)
$activeToday = $db->query("SELECT COUNT(*) FROM job_applications WHERE DATE(created_at) = CURDATE()")->fetchColumn();

// Success rate (Offer / Total * 100)
$offers = $db->query("SELECT COUNT(*) FROM job_applications WHERE status = 'Offer'")->fetchColumn();
$successRate = $totalApplications > 0 ? round(($offers / $totalApplications) * 100, 1) : 0;

// Status counts
$stmt = $db->query("SELECT status, COUNT(*) as count FROM job_applications GROUP BY status ORDER BY count DESC");
$statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode([
    'success' => true,
    'data' => [
        'totalUsers' => $totalUsers,
        'totalApplications' => $totalApplications,
        'activeToday' => $activeToday,
        'successRate' => $successRate,
        'statusCounts' => $statusCounts
    ]
]);
