<?php
require_once 'config/database.php';

echo "=== Seeding Database with Sample Data ===\n\n";

$database = new Database();
$db = $database->getConnection();

try {
    // 1. Create Admin User
    echo "1. Creating Admin User...\n";
    $adminPassword = password_hash('Admin123', PASSWORD_BCRYPT);
    
    $query = "INSERT INTO users (full_name, email, password, phone, career_preferences) 
              VALUES (:full_name, :email, :password, :phone, :career_preferences)";
    $stmt = $db->prepare($query);
    
    $stmt->execute([
        ':full_name' => 'Admin User',
        ':email' => 'admin@jobtracker.com',
        ':password' => $adminPassword,
        ':phone' => '+1-555-0100',
        ':career_preferences' => 'System Administrator'
    ]);
    
    $adminId = $db->lastInsertId();
    echo "   ✓ Admin created (ID: $adminId)\n";
    echo "   Email: admin@jobtracker.com\n";
    echo "   Password: Admin123\n\n";
    
    // 2. Create Job Seekers
    echo "2. Creating Job Seekers...\n";
    
    $jobSeekers = [
        [
            'name' => 'John Smith',
            'email' => 'john.smith@email.com',
            'password' => 'password123',
            'phone' => '+1-555-0101',
            'preferences' => 'Software Development, Full Stack'
        ],
        [
            'name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@email.com',
            'password' => 'password123',
            'phone' => '+1-555-0102',
            'preferences' => 'Data Science, Machine Learning'
        ],
        [
            'name' => 'Michael Chen',
            'email' => 'michael.chen@email.com',
            'password' => 'password123',
            'phone' => '+1-555-0103',
            'preferences' => 'DevOps, Cloud Engineering'
        ],
        [
            'name' => 'Emily Davis',
            'email' => 'emily.davis@email.com',
            'password' => 'password123',
            'phone' => '+1-555-0104',
            'preferences' => 'UI/UX Design, Frontend Development'
        ],
        [
            'name' => 'David Martinez',
            'email' => 'david.martinez@email.com',
            'password' => 'password123',
            'phone' => '+1-555-0105',
            'preferences' => 'Project Management, Agile'
        ]
    ];
    
    $userIds = [];
    foreach ($jobSeekers as $seeker) {
        $hashedPassword = password_hash($seeker['password'], PASSWORD_BCRYPT);
        
        $stmt->execute([
            ':full_name' => $seeker['name'],
            ':email' => $seeker['email'],
            ':password' => $hashedPassword,
            ':phone' => $seeker['phone'],
            ':career_preferences' => $seeker['preferences']
        ]);
        
        $userId = $db->lastInsertId();
        $userIds[] = $userId;
        echo "   ✓ {$seeker['name']} (ID: $userId) - {$seeker['email']}\n";
    }
    echo "\n";
    
    // 3. Create Sample Job Applications
    echo "3. Creating Sample Job Applications...\n";
    
    $companies = [
        [
            'name' => 'Google',
            'positions' => ['Software Engineer', 'Senior Developer', 'Tech Lead'],
            'contacts' => ['Sarah Chen', 'Michael Roberts', 'Jennifer Lee'],
            'domain' => 'google.com'
        ],
        [
            'name' => 'Microsoft',
            'positions' => ['Cloud Engineer', 'Full Stack Developer', 'DevOps Engineer'],
            'contacts' => ['David Wilson', 'Emily Brown', 'James Taylor'],
            'domain' => 'microsoft.com'
        ],
        [
            'name' => 'Amazon',
            'positions' => ['Backend Developer', 'Data Engineer', 'Solutions Architect'],
            'contacts' => ['Lisa Anderson', 'Robert Martinez', 'Amanda White'],
            'domain' => 'amazon.com'
        ],
        [
            'name' => 'Meta',
            'positions' => ['Frontend Developer', 'Mobile Developer', 'Product Engineer'],
            'contacts' => ['Kevin Zhang', 'Maria Garcia', 'Thomas Moore'],
            'domain' => 'meta.com'
        ],
        [
            'name' => 'Apple',
            'positions' => ['iOS Developer', 'Software Engineer', 'UI/UX Designer'],
            'contacts' => ['Jessica Kim', 'Daniel Park', 'Rachel Green'],
            'domain' => 'apple.com'
        ],
        [
            'name' => 'Netflix',
            'positions' => ['Senior Engineer', 'Data Scientist', 'Platform Engineer'],
            'contacts' => ['Chris Johnson', 'Nicole Davis', 'Brandon Lee'],
            'domain' => 'netflix.com'
        ],
        [
            'name' => 'Tesla',
            'positions' => ['Embedded Engineer', 'Software Developer', 'Automation Engineer'],
            'contacts' => ['Alex Turner', 'Sophia Martinez', 'Ryan Cooper'],
            'domain' => 'tesla.com'
        ],
        [
            'name' => 'Spotify',
            'positions' => ['Backend Engineer', 'Data Analyst', 'ML Engineer'],
            'contacts' => ['Emma Wilson', 'Lucas Brown', 'Olivia Taylor'],
            'domain' => 'spotify.com'
        ],
        [
            'name' => 'Adobe',
            'positions' => ['Creative Developer', 'Frontend Engineer', 'UX Designer'],
            'contacts' => ['Nathan Scott', 'Isabella Clark', 'Ethan Lewis'],
            'domain' => 'adobe.com'
        ],
        [
            'name' => 'Salesforce',
            'positions' => ['Cloud Developer', 'Full Stack Engineer', 'Technical Lead'],
            'contacts' => ['Mia Robinson', 'Jacob Walker', 'Ava Hall'],
            'domain' => 'salesforce.com'
        ]
    ];
    
    $statuses = ['Applied', 'Screening', 'Interview', 'Offer', 'Rejected'];
    $notes = [
        'Great company culture, excited about this opportunity. Looking forward to the technical interview.',
        'Competitive salary range $120k-$150k, excellent benefits package including health, dental, and 401k.',
        'Remote work available with flexible hours. Team uses modern tech stack including React and Node.js.',
        'Challenging technical interview process with 4 rounds. Preparing data structures and algorithms.',
        'Team seems very collaborative and innovative. Strong emphasis on code quality and best practices.',
        'Strong focus on work-life balance. Company offers unlimited PTO and professional development budget.',
        'Opportunity for career growth and learning. Mentorship program available for junior developers.',
        'Impressive tech stack: AWS, Docker, Kubernetes, microservices architecture. Exciting project!',
        'Recruiter was very responsive and helpful. Company has great Glassdoor reviews.',
        'Position involves working on cutting-edge AI/ML projects. Great learning opportunity.'
    ];
    
    $appQuery = "INSERT INTO job_applications 
                 (user_id, company_name, position, job_link, date_applied, contact_person, 
                  contact_email, contact_phone, status, notes) 
                 VALUES (:user_id, :company_name, :position, :job_link, :date_applied, 
                         :contact_person, :contact_email, :contact_phone, :status, :notes)";
    $appStmt = $db->prepare($appQuery);
    
    $applicationCount = 0;
    $applicationIds = [];
    
    foreach ($userIds as $userId) {
        // Each user gets 3-5 applications
        $numApps = rand(3, 5);
        
        for ($i = 0; $i < $numApps; $i++) {
            $company = $companies[array_rand($companies)];
            $position = $company['positions'][array_rand($company['positions'])];
            $contactPerson = $company['contacts'][array_rand($company['contacts'])];
            $status = $statuses[array_rand($statuses)];
            $daysAgo = rand(1, 60);
            $dateApplied = date('Y-m-d', strtotime("-$daysAgo days"));
            
            // Generate realistic job link
            $jobId = strtolower(str_replace(' ', '-', $position)) . '-' . rand(10000, 99999);
            $jobLink = "https://careers.{$company['domain']}/jobs/{$jobId}";
            
            // Generate contact phone
            $contactPhone = '+1-' . rand(200, 999) . '-' . rand(100, 999) . '-' . rand(1000, 9999);
            
            $appStmt->execute([
                ':user_id' => $userId,
                ':company_name' => $company['name'],
                ':position' => $position,
                ':job_link' => $jobLink,
                ':date_applied' => $dateApplied,
                ':contact_person' => $contactPerson,
                ':contact_email' => strtolower(str_replace(' ', '.', $contactPerson)) . '@' . $company['domain'],
                ':contact_phone' => $contactPhone,
                ':status' => $status,
                ':notes' => $notes[array_rand($notes)]
            ]);
            
            $applicationIds[] = $db->lastInsertId();
            $applicationCount++;
        }
    }
    
    echo "   ✓ Created $applicationCount job applications\n\n";
    
    // 4. Create Sample Reminders
    echo "4. Creating Sample Reminders...\n";
    
    $reminderQuery = "INSERT INTO reminders (application_id, reminder_type, reminder_date, description) 
                      VALUES (:application_id, :reminder_type, :reminder_date, :description)";
    $reminderStmt = $db->prepare($reminderQuery);
    
    // Get some applications with Interview status
    $interviewApps = $db->query("SELECT id FROM job_applications WHERE status = 'Interview' LIMIT 5")->fetchAll(PDO::FETCH_COLUMN);
    
    $reminderCount = 0;
    foreach ($interviewApps as $appId) {
        $daysAhead = rand(1, 14);
        $reminderDate = date('Y-m-d H:i:s', strtotime("+$daysAhead days"));
        
        $reminderStmt->execute([
            ':application_id' => $appId,
            ':reminder_type' => 'Interview',
            ':reminder_date' => $reminderDate,
            ':description' => 'Technical interview scheduled - prepare coding questions'
        ]);
        
        $reminderCount++;
    }
    
    echo "   ✓ Created $reminderCount reminders\n\n";
    
    // 5. Create Sample Documents
    echo "5. Creating Sample Documents...\n";
    
    $documentTypes = ['Resume', 'Cover Letter', 'Job Description', 'Other'];
    $documentQuery = "INSERT INTO documents (application_id, document_type, file_name, file_path, file_size) 
                      VALUES (:application_id, :document_type, :file_name, :file_path, :file_size)";
    $documentStmt = $db->prepare($documentQuery);
    
    $documentCount = 0;
    // Add 1-3 documents to random applications
    $appsWithDocs = array_rand(array_flip($applicationIds), min(15, count($applicationIds)));
    if (!is_array($appsWithDocs)) $appsWithDocs = [$appsWithDocs];
    
    foreach ($appsWithDocs as $appId) {
        $numDocs = rand(1, 3);
        $usedTypes = [];
        
        for ($i = 0; $i < $numDocs; $i++) {
            // Don't duplicate document types for same application
            $availableTypes = array_diff($documentTypes, $usedTypes);
            if (empty($availableTypes)) break;
            
            $docType = $availableTypes[array_rand($availableTypes)];
            $usedTypes[] = $docType;
            
            // Generate realistic file names
            $fileNames = [
                'Resume' => ['Resume_2024.pdf', 'John_Doe_Resume.pdf', 'CV_Updated.pdf', 'Professional_Resume.pdf'],
                'Cover Letter' => ['Cover_Letter.pdf', 'CL_Company.pdf', 'Letter_of_Interest.pdf', 'Application_Letter.pdf'],
                'Job Description' => ['Job_Description.pdf', 'Position_Details.pdf', 'Role_Requirements.pdf', 'JD.pdf'],
                'Other' => ['Portfolio.pdf', 'References.pdf', 'Certifications.pdf', 'Transcript.pdf']
            ];
            
            $fileName = $fileNames[$docType][array_rand($fileNames[$docType])];
            $fileSize = rand(50000, 500000); // 50KB to 500KB
            $filePath = 'uploads/' . uniqid() . '_' . $fileName;
            
            $documentStmt->execute([
                ':application_id' => $appId,
                ':document_type' => $docType,
                ':file_name' => $fileName,
                ':file_path' => $filePath,
                ':file_size' => $fileSize
            ]);
            
            $documentCount++;
        }
    }
    
    echo "   ✓ Created $documentCount documents\n\n";
    
    // 6. Display Summary
    echo "=== Seeding Complete! ===\n\n";
    
    echo "📊 Summary:\n";
    echo "   Users: " . (count($jobSeekers) + 1) . " (1 admin + " . count($jobSeekers) . " job seekers)\n";
    echo "   Applications: $applicationCount\n";
    echo "   Documents: $documentCount\n";
    echo "   Reminders: $reminderCount\n\n";
    
    echo "👤 Login Credentials:\n\n";
    echo "   ADMIN:\n";
    echo "   Email: admin@jobtracker.com\n";
    echo "   Password: Admin123\n\n";
    
    echo "   JOB SEEKERS (all use password: password123):\n";
    foreach ($jobSeekers as $seeker) {
        echo "   - {$seeker['email']}\n";
    }
    
    echo "\n🌐 Access: http://localhost/jobportal/\n";
    
} catch (PDOException $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
