<?php
require_once __DIR__ . '/../config/config.php';

class EmailHelper {
    
    public static function sendEmail($to, $subject, $htmlContent) {
        $apiKey = SENDGRID_API_KEY;
        
        $data = [
            'personalizations' => [[
                'to' => [['email' => $to]],
                'subject' => $subject
            ]],
            'from' => [
                'email' => FROM_EMAIL,
                'name' => FROM_NAME
            ],
            'content' => [[
                'type' => 'text/html',
                'value' => $htmlContent
            ]]
        ];
        
        $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode >= 200 && $httpCode < 300;
    }
    
    public static function sendReminderEmail($to, $reminderType, $companyName, $position, $reminderDate) {
        $subject = "Reminder: {$reminderType} for {$position} at {$companyName}";
        
        $html = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #2563eb;'>Job Application Reminder</h2>
            <p>This is a reminder about your upcoming {$reminderType}:</p>
            <div style='background: #f3f4f6; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                <p><strong>Company:</strong> {$companyName}</p>
                <p><strong>Position:</strong> {$position}</p>
                <p><strong>Type:</strong> {$reminderType}</p>
                <p><strong>Date:</strong> {$reminderDate}</p>
            </div>
            <p>Good luck with your application!</p>
        </div>
        ";
        
        return self::sendEmail($to, $subject, $html);
    }
}
