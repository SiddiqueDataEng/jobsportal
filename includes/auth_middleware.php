<?php
require_once __DIR__ . '/jwt_helper.php';

function authenticate() {
    $headers = getallheaders();
    
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'No token provided']);
        exit();
    }
    
    $authHeader = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $authHeader);
    
    $decoded = JWTHelper::decode($token);
    
    if (!$decoded) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
        exit();
    }
    
    return $decoded;
}
