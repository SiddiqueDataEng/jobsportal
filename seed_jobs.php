<?php
require_once 'config/database.php';

echo "=== Seeding Job Postings ===\n\n";

$database = new Database();
$db = $database->getConnection();

try {
    // Get admin user ID
    $adminQuery = "SELECT id FROM users WHERE role = 'admin' LIMIT 1";
    $adminStmt = $db->query($adminQuery);
    $admin = $adminStmt->fetch(PDO::FETCH_ASSOC);
    $adminId = $admin['id'];
    
    echo "1. Creating job postings...\n";
    
    $jobs = [
        [
            'company' => 'Google',
            'position' => 'Senior Software Engineer',
            'description' => 'We are looking for an experienced Software Engineer to join our team. You will be working on cutting-edge technologies and solving complex problems at scale.',
            'requirements' => '• 5+ years of software development experience\n• Strong knowledge of Java, Python, or Go\n• Experience with distributed systems\n• BS/MS in Computer Science or related field',
            'salary' => '$150,000 - $200,000',
            'location' => 'Mountain View, CA',
            'type' => 'Full-time',
            'contact' => 'Sarah Chen',
            'email' => 'sarah.chen@google.com',
            'phone' => '+1-650-555-0100',
            'link' => 'https://careers.google.com/jobs/senior-software-engineer-12345'
        ],
        [
            'company' => 'Microsoft',
            'position' => 'Cloud Solutions Architect',
            'description' => 'Join our Azure team to design and implement cloud solutions for enterprise clients. Help organizations transform their infrastructure and applications.',
            'requirements' => '• 7+ years in cloud architecture\n• Azure/AWS certifications preferred\n• Strong communication skills\n• Experience with microservices and containers',
            'salary' => '$140,000 - $180,000',
            'location' => 'Redmond, WA',
            'type' => 'Full-time',
            'contact' => 'Michael Roberts',
            'email' => 'michael.roberts@microsoft.com',
            'phone' => '+1-425-555-0200',
            'link' => 'https://careers.microsoft.com/jobs/cloud-architect-67890'
        ],
        [
            'company' => 'Amazon',
            'position' => 'Data Engineer',
            'description' => 'Build and maintain data pipelines that power Amazon\'s analytics and machine learning systems. Work with petabytes of data daily.',
            'requirements' => '• 3+ years in data engineering\n• Proficiency in SQL, Python, Spark\n• Experience with AWS services (S3, EMR, Redshift)\n• Strong problem-solving skills',
            'salary' => '$130,000 - $170,000',
            'location' => 'Seattle, WA',
            'type' => 'Full-time',
            'contact' => 'Lisa Anderson',
            'email' => 'lisa.anderson@amazon.com',
            'phone' => '+1-206-555-0300',
            'link' => 'https://careers.amazon.com/jobs/data-engineer-54321'
        ],
        [
            'company' => 'Meta',
            'position' => 'Frontend Developer',
            'description' => 'Create amazing user experiences for billions of users. Work with React, GraphQL, and modern web technologies.',
            'requirements' => '• 4+ years of frontend development\n• Expert in React, TypeScript, CSS\n• Experience with performance optimization\n• Portfolio of web applications',
            'salary' => '$135,000 - $175,000',
            'location' => 'Menlo Park, CA',
            'type' => 'Full-time',
            'contact' => 'Kevin Zhang',
            'email' => 'kevin.zhang@meta.com',
            'phone' => '+1-650-555-0400',
            'link' => 'https://careers.meta.com/jobs/frontend-developer-98765'
        ],
        [
            'company' => 'Apple',
            'position' => 'iOS Developer',
            'description' => 'Develop innovative features for iOS applications used by millions. Join a team passionate about creating the best user experience.',
            'requirements' => '• 5+ years iOS development experience\n• Expert in Swift and SwiftUI\n• Published apps in App Store\n• Strong understanding of iOS frameworks',
            'salary' => '$145,000 - $190,000',
            'location' => 'Cupertino, CA',
            'type' => 'Full-time',
            'contact' => 'Jessica Kim',
            'email' => 'jessica.kim@apple.com',
            'phone' => '+1-408-555-0500',
            'link' => 'https://careers.apple.com/jobs/ios-developer-11223'
        ],
        [
            'company' => 'Netflix',
            'position' => 'Machine Learning Engineer',
            'description' => 'Build recommendation systems and personalization algorithms that delight our 200M+ subscribers worldwide.',
            'requirements' => '• MS/PhD in CS, ML, or related field\n• 3+ years in machine learning\n• Experience with TensorFlow/PyTorch\n• Strong Python and Scala skills',
            'salary' => '$160,000 - $220,000',
            'location' => 'Los Gatos, CA',
            'type' => 'Full-time',
            'contact' => 'Chris Johnson',
            'email' => 'chris.johnson@netflix.com',
            'phone' => '+1-408-555-0600',
            'link' => 'https://careers.netflix.com/jobs/ml-engineer-44556'
        ],
        [
            'company' => 'Tesla',
            'position' => 'Embedded Software Engineer',
            'description' => 'Develop software for Tesla vehicles and energy products. Work on autonomous driving, battery management, and vehicle control systems.',
            'requirements' => '• 4+ years embedded systems experience\n• Proficiency in C/C++\n• Experience with RTOS and hardware interfaces\n• Automotive experience preferred',
            'salary' => '$125,000 - $165,000',
            'location' => 'Palo Alto, CA',
            'type' => 'Full-time',
            'contact' => 'Alex Turner',
            'email' => 'alex.turner@tesla.com',
            'phone' => '+1-650-555-0700',
            'link' => 'https://careers.tesla.com/jobs/embedded-engineer-77889'
        ],
        [
            'company' => 'Spotify',
            'position' => 'Backend Engineer',
            'description' => 'Build scalable backend services that power music streaming for 400M+ users. Work with microservices, Kubernetes, and cloud infrastructure.',
            'requirements' => '• 3+ years backend development\n• Strong Java or Python skills\n• Experience with distributed systems\n• Knowledge of databases and caching',
            'salary' => '$120,000 - $160,000',
            'location' => 'New York, NY',
            'type' => 'Full-time',
            'contact' => 'Emma Wilson',
            'email' => 'emma.wilson@spotify.com',
            'phone' => '+1-212-555-0800',
            'link' => 'https://careers.spotify.com/jobs/backend-engineer-33445'
        ],
        [
            'company' => 'Adobe',
            'position' => 'UX Designer',
            'description' => 'Design intuitive and beautiful user experiences for Creative Cloud applications. Collaborate with product managers and engineers.',
            'requirements' => '• 5+ years UX design experience\n• Portfolio showcasing design work\n• Proficiency in Figma, Adobe XD\n• Strong understanding of design systems',
            'salary' => '$110,000 - $150,000',
            'location' => 'San Jose, CA',
            'type' => 'Full-time',
            'contact' => 'Nathan Scott',
            'email' => 'nathan.scott@adobe.com',
            'phone' => '+1-408-555-0900',
            'link' => 'https://careers.adobe.com/jobs/ux-designer-66778'
        ],
        [
            'company' => 'Salesforce',
            'position' => 'DevOps Engineer',
            'description' => 'Automate infrastructure, improve deployment pipelines, and ensure high availability of Salesforce services.',
            'requirements' => '• 4+ years DevOps experience\n• Expert in Docker, Kubernetes, Terraform\n• Strong scripting skills (Python, Bash)\n• Experience with CI/CD tools',
            'salary' => '$130,000 - $170,000',
            'location' => 'San Francisco, CA',
            'type' => 'Full-time',
            'contact' => 'Mia Robinson',
            'email' => 'mia.robinson@salesforce.com',
            'phone' => '+1-415-555-1000',
            'link' => 'https://careers.salesforce.com/jobs/devops-engineer-99001'
        ]
    ];
    
    $query = "INSERT INTO job_postings 
              (company_name, position, description, requirements, salary_range, location, 
               job_type, contact_person, contact_email, contact_phone, job_link, 
               posted_by, status, posted_date, deadline) 
              VALUES (:company, :position, :description, :requirements, :salary, :location, 
                      :type, :contact, :email, :phone, :link, :posted_by, 'Active', CURDATE(), 
                      DATE_ADD(CURDATE(), INTERVAL 30 DAY))";
    
    $stmt = $db->prepare($query);
    
    $count = 0;
    foreach ($jobs as $job) {
        $stmt->execute([
            ':company' => $job['company'],
            ':position' => $job['position'],
            ':description' => $job['description'],
            ':requirements' => $job['requirements'],
            ':salary' => $job['salary'],
            ':location' => $job['location'],
            ':type' => $job['type'],
            ':contact' => $job['contact'],
            ':email' => $job['email'],
            ':phone' => $job['phone'],
            ':link' => $job['link'],
            ':posted_by' => $adminId
        ]);
        $count++;
        echo "   ✓ {$job['company']} - {$job['position']}\n";
    }
    
    echo "\n=== Seeding Complete! ===\n\n";
    echo "Created $count job postings\n";
    echo "All jobs are Active and visible on landing page\n\n";
    echo "Access: http://localhost/jobportal/\n";
    
} catch (PDOException $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
