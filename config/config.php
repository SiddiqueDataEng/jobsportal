<?php
// JWT Configuration
define('JWT_SECRET_KEY', 'your_secret_key_here_change_in_production');
define('JWT_ALGORITHM', 'HS256');
define('JWT_EXPIRATION', 86400); // 24 hours

// SendGrid Configuration
define('SENDGRID_API_KEY', 'your_sendgrid_api_key_here');
define('FROM_EMAIL', 'noreply@jobtracker.com');
define('FROM_NAME', 'Job Tracker');

// Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'txt']);

// Base URL
define('BASE_URL', 'http://localhost/jobportal/');
