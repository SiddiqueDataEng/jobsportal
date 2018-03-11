<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/email_helper.php';

$database = new Database();
$db = $database->getConnection();

// Get reminders due in next 24 hours that haven't been sent
$query = "SELECT r.*, ja.company_name, ja.position, u.email 
          FROM reminders r
          JOIN job_applications ja ON r.application_id = ja.id
          JOIN users u ON ja.user_id = u.id
          WHERE r.reminder_date <= DATE_ADD(NOW(), INTERVAL 24 HOUR)
          AND r.is_sent = 0";

$stmt = $db->prepare($query);
$stmt->execute();

$reminders = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($reminders as $reminder) {
    $sent = EmailHelper::sendReminderEmail(
        $reminder['email'],
        $reminder['reminder_type'],
        $reminder['company_name'],
        $reminder['position'],
        $reminder['reminder_date']
    );
    
    if ($sent) {
        $updateQuery = "UPDATE reminders SET is_sent = 1 WHERE id = :id";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindParam(":id", $reminder['id']);
        $updateStmt->execute();
        
        echo "Reminder sent for: {$reminder['company_name']} - {$reminder['position']}\n";
    }
}

echo "Reminder processing complete.\n";
