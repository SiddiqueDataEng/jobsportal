<?php
echo "=== Job Tracker Database Setup ===\n\n";

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "db_jobtracker";

try {
    // Connect to MySQL server (without database)
    echo "1. Connecting to MySQL server...\n";
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✓ Connected successfully\n\n";

    // Create database
    echo "2. Creating database 'db_jobtracker'...\n";
    $conn->exec("CREATE DATABASE IF NOT EXISTS $database");
    echo "   ✓ Database created/verified\n\n";

    // Connect to the database
    echo "3. Connecting to database...\n";
    $conn->exec("USE $database");
    echo "   ✓ Connected to db_jobtracker\n\n";

    // Create Users Table
    echo "4. Creating 'users' table...\n";
    $conn->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            career_preferences TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "   ✓ Users table created\n\n";

    // Create Job Applications Table
    echo "5. Creating 'job_applications' table...\n";
    $conn->exec("
        CREATE TABLE IF NOT EXISTS job_applications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            company_name VARCHAR(150) NOT NULL,
            position VARCHAR(150) NOT NULL,
            job_link VARCHAR(500),
            date_applied DATE NOT NULL,
            contact_person VARCHAR(100),
            contact_email VARCHAR(100),
            contact_phone VARCHAR(20),
            status ENUM('Applied', 'Screening', 'Interview', 'Offer', 'Rejected') DEFAULT 'Applied',
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "   ✓ Job applications table created\n\n";

    // Create Documents Table
    echo "6. Creating 'documents' table...\n";
    $conn->exec("
        CREATE TABLE IF NOT EXISTS documents (
            id INT AUTO_INCREMENT PRIMARY KEY,
            application_id INT NOT NULL,
            document_type ENUM('Resume', 'Cover Letter', 'Job Description', 'Other') NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(500) NOT NULL,
            file_size INT NOT NULL,
            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (application_id) REFERENCES job_applications(id) ON DELETE CASCADE
        )
    ");
    echo "   ✓ Documents table created\n\n";

    // Create Reminders Table
    echo "7. Creating 'reminders' table...\n";
    $conn->exec("
        CREATE TABLE IF NOT EXISTS reminders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            application_id INT NOT NULL,
            reminder_type ENUM('Interview', 'Deadline', 'Follow-up', 'Other') NOT NULL,
            reminder_date DATETIME NOT NULL,
            description TEXT,
            is_sent BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (application_id) REFERENCES job_applications(id) ON DELETE CASCADE
        )
    ");
    echo "   ✓ Reminders table created\n\n";

    // Create Indexes
    echo "8. Creating indexes for performance...\n";
    try {
        $conn->exec("CREATE INDEX idx_user_email ON users(email)");
    } catch(PDOException $e) {
        // Index might already exist
    }
    try {
        $conn->exec("CREATE INDEX idx_application_user ON job_applications(user_id)");
    } catch(PDOException $e) {
        // Index might already exist
    }
    try {
        $conn->exec("CREATE INDEX idx_application_status ON job_applications(status)");
    } catch(PDOException $e) {
        // Index might already exist
    }
    try {
        $conn->exec("CREATE INDEX idx_reminder_date ON reminders(reminder_date, is_sent)");
    } catch(PDOException $e) {
        // Index might already exist
    }
    echo "   ✓ Indexes created\n\n";

    // Verify tables
    echo "9. Verifying tables...\n";
    $result = $conn->query("SHOW TABLES");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        echo "   ✓ $table\n";
    }
    
    echo "\n=== Setup Complete! ===\n";
    echo "Database: db_jobtracker\n";
    echo "Tables created: " . count($tables) . "\n";
    echo "\nYou can now access the application at: http://localhost/jobportal/\n";

} catch(PDOException $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
