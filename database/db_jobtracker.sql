-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 21, 2026 at 02:05 PM
-- Server version: 9.1.0
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_jobtracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `application_id` int NOT NULL,
  `document_type` enum('Resume','Cover Letter','Job Description','Other') COLLATE utf8mb4_general_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `file_size` int NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `application_id`, `document_type`, `file_name`, `file_path`, `file_size`, `uploaded_at`) VALUES
(1, 1, 'Job Description', 'JD.pdf', 'uploads/6999a56521397_JD.pdf', 400022, '2026-02-21 12:30:29'),
(2, 2, 'Cover Letter', 'CL_Company.pdf', 'uploads/6999a56521962_CL_Company.pdf', 196572, '2026-02-21 12:30:29'),
(3, 3, 'Cover Letter', 'Cover_Letter.pdf', 'uploads/6999a56521a69_Cover_Letter.pdf', 238453, '2026-02-21 12:30:29'),
(4, 5, 'Job Description', 'Role_Requirements.pdf', 'uploads/6999a56521b26_Role_Requirements.pdf', 96473, '2026-02-21 12:30:29'),
(5, 5, 'Other', 'Portfolio.pdf', 'uploads/6999a56521bcb_Portfolio.pdf', 81978, '2026-02-21 12:30:29'),
(6, 5, 'Resume', 'John_Doe_Resume.pdf', 'uploads/6999a56521c62_John_Doe_Resume.pdf', 313594, '2026-02-21 12:30:29'),
(7, 6, 'Resume', 'Professional_Resume.pdf', 'uploads/6999a56521d07_Professional_Resume.pdf', 76643, '2026-02-21 12:30:29'),
(8, 6, 'Job Description', 'JD.pdf', 'uploads/6999a56521dbc_JD.pdf', 257698, '2026-02-21 12:30:29'),
(9, 6, 'Other', 'Certifications.pdf', 'uploads/6999a56521e47_Certifications.pdf', 256729, '2026-02-21 12:30:29'),
(10, 7, 'Resume', 'CV_Updated.pdf', 'uploads/6999a56521ecc_CV_Updated.pdf', 122074, '2026-02-21 12:30:29'),
(11, 8, 'Job Description', 'Role_Requirements.pdf', 'uploads/6999a56521fe3_Role_Requirements.pdf', 472564, '2026-02-21 12:30:29'),
(12, 8, 'Other', 'Transcript.pdf', 'uploads/6999a56522057_Transcript.pdf', 445254, '2026-02-21 12:30:29'),
(13, 8, 'Resume', 'Resume_2024.pdf', 'uploads/6999a56522130_Resume_2024.pdf', 66834, '2026-02-21 12:30:29'),
(14, 9, 'Job Description', 'JD.pdf', 'uploads/6999a565224e1_JD.pdf', 59957, '2026-02-21 12:30:29'),
(15, 9, 'Resume', 'John_Doe_Resume.pdf', 'uploads/6999a56522624_John_Doe_Resume.pdf', 308052, '2026-02-21 12:30:29'),
(16, 10, 'Cover Letter', 'CL_Company.pdf', 'uploads/6999a565226dc_CL_Company.pdf', 129421, '2026-02-21 12:30:29'),
(17, 13, 'Job Description', 'JD.pdf', 'uploads/6999a56522763_JD.pdf', 130923, '2026-02-21 12:30:29'),
(18, 13, 'Resume', 'Resume_2024.pdf', 'uploads/6999a565227de_Resume_2024.pdf', 491856, '2026-02-21 12:30:29'),
(19, 13, 'Cover Letter', 'Application_Letter.pdf', 'uploads/6999a56522855_Application_Letter.pdf', 307908, '2026-02-21 12:30:29'),
(20, 14, 'Other', 'References.pdf', 'uploads/6999a565228c8_References.pdf', 56624, '2026-02-21 12:30:29'),
(21, 14, 'Resume', 'John_Doe_Resume.pdf', 'uploads/6999a56522936_John_Doe_Resume.pdf', 430643, '2026-02-21 12:30:29'),
(22, 15, 'Resume', 'Resume_2024.pdf', 'uploads/6999a565229a3_Resume_2024.pdf', 271644, '2026-02-21 12:30:29'),
(23, 15, 'Job Description', 'JD.pdf', 'uploads/6999a56522a0f_JD.pdf', 395665, '2026-02-21 12:30:29'),
(24, 15, 'Cover Letter', 'CL_Company.pdf', 'uploads/6999a56522a7a_CL_Company.pdf', 176015, '2026-02-21 12:30:29'),
(25, 17, 'Job Description', 'Role_Requirements.pdf', 'uploads/6999a56522ae5_Role_Requirements.pdf', 376030, '2026-02-21 12:30:29'),
(26, 17, 'Cover Letter', 'CL_Company.pdf', 'uploads/6999a56522b53_CL_Company.pdf', 253203, '2026-02-21 12:30:29'),
(27, 19, 'Resume', 'John_Doe_Resume.pdf', 'uploads/6999a56522bbd_John_Doe_Resume.pdf', 187085, '2026-02-21 12:30:29'),
(28, 19, 'Cover Letter', 'CL_Company.pdf', 'uploads/6999a56522c28_CL_Company.pdf', 251422, '2026-02-21 12:30:29'),
(29, 20, 'Cover Letter', 'CL_Company.pdf', 'uploads/6999a56522c92_CL_Company.pdf', 328581, '2026-02-21 12:30:29'),
(30, 20, 'Other', 'References.pdf', 'uploads/6999a56522cfc_References.pdf', 281845, '2026-02-21 12:30:29'),
(31, 20, 'Job Description', 'Role_Requirements.pdf', 'uploads/6999a56522d67_Role_Requirements.pdf', 248264, '2026-02-21 12:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

DROP TABLE IF EXISTS `job_applications`;
CREATE TABLE IF NOT EXISTS `job_applications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `job_posting_id` int DEFAULT NULL,
  `company_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `position` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `job_link` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_applied` date NOT NULL,
  `contact_person` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Applied','Shortlisted','Screening','Interview','Offer','Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Applied',
  `interview_date` datetime DEFAULT NULL,
  `interview_location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `interview_notes` text COLLATE utf8mb4_general_ci,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_application_user` (`user_id`),
  KEY `idx_application_status` (`status`),
  KEY `idx_application_job` (`job_posting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `user_id`, `job_posting_id`, `company_name`, `position`, `job_link`, `date_applied`, `contact_person`, `contact_email`, `contact_phone`, `status`, `interview_date`, `interview_location`, `interview_notes`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 'Salesforce', 'Technical Lead', 'https://careers.salesforce.com/jobs/technical-lead-85747', '2026-02-04', 'Mia Robinson', 'mia.robinson@salesforce.com', '+1-278-218-3497', 'Rejected', NULL, NULL, NULL, 'Strong focus on work-life balance. Company offers unlimited PTO and professional development budget.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(2, 2, NULL, 'Spotify', 'Backend Engineer', 'https://careers.spotify.com/jobs/backend-engineer-12765', '2026-01-13', 'Olivia Taylor', 'olivia.taylor@spotify.com', '+1-997-590-8263', 'Offer', NULL, NULL, NULL, 'Competitive salary range $120k-$150k, excellent benefits package including health, dental, and 401k.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(3, 2, NULL, 'Netflix', 'Senior Engineer', 'https://careers.netflix.com/jobs/senior-engineer-46648', '2026-01-04', 'Brandon Lee', 'brandon.lee@netflix.com', '+1-883-457-4235', 'Screening', NULL, NULL, NULL, 'Impressive tech stack: AWS, Docker, Kubernetes, microservices architecture. Exciting project!', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(4, 2, NULL, 'Tesla', 'Automation Engineer', 'https://careers.tesla.com/jobs/automation-engineer-31160', '2025-12-26', 'Sophia Martinez', 'sophia.martinez@tesla.com', '+1-383-862-5355', 'Rejected', NULL, NULL, NULL, 'Competitive salary range $120k-$150k, excellent benefits package including health, dental, and 401k.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(5, 3, NULL, 'Adobe', 'UX Designer', 'https://careers.adobe.com/jobs/ux-designer-64038', '2026-01-22', 'Ethan Lewis', 'ethan.lewis@adobe.com', '+1-761-161-6519', 'Offer', NULL, NULL, NULL, 'Team seems very collaborative and innovative. Strong emphasis on code quality and best practices.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(6, 3, NULL, 'Apple', 'Software Engineer', 'https://careers.apple.com/jobs/software-engineer-19911', '2026-01-27', 'Jessica Kim', 'jessica.kim@apple.com', '+1-359-240-6958', 'Interview', NULL, NULL, NULL, 'Opportunity for career growth and learning. Mentorship program available for junior developers.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(7, 3, NULL, 'Microsoft', 'DevOps Engineer', 'https://careers.microsoft.com/jobs/devops-engineer-98189', '2025-12-31', 'David Wilson', 'david.wilson@microsoft.com', '+1-593-845-3896', 'Offer', NULL, NULL, NULL, 'Challenging technical interview process with 4 rounds. Preparing data structures and algorithms.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(8, 3, NULL, 'Salesforce', 'Cloud Developer', 'https://careers.salesforce.com/jobs/cloud-developer-67567', '2026-01-05', 'Jacob Walker', 'jacob.walker@salesforce.com', '+1-277-279-7951', 'Applied', NULL, NULL, NULL, 'Challenging technical interview process with 4 rounds. Preparing data structures and algorithms.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(9, 3, NULL, 'Adobe', 'Creative Developer', 'https://careers.adobe.com/jobs/creative-developer-66769', '2026-02-04', 'Isabella Clark', 'isabella.clark@adobe.com', '+1-923-401-2228', 'Interview', NULL, NULL, NULL, 'Strong focus on work-life balance. Company offers unlimited PTO and professional development budget.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(10, 4, NULL, 'Google', 'Software Engineer', 'https://careers.google.com/jobs/software-engineer-40168', '2026-02-03', 'Jennifer Lee', 'jennifer.lee@google.com', '+1-772-594-1209', 'Rejected', NULL, NULL, NULL, 'Remote work available with flexible hours. Team uses modern tech stack including React and Node.js.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(11, 4, NULL, 'Microsoft', 'DevOps Engineer', 'https://careers.microsoft.com/jobs/devops-engineer-64689', '2026-01-26', 'David Wilson', 'david.wilson@microsoft.com', '+1-250-924-4179', 'Rejected', NULL, NULL, NULL, 'Strong focus on work-life balance. Company offers unlimited PTO and professional development budget.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(12, 4, NULL, 'Microsoft', 'Cloud Engineer', 'https://careers.microsoft.com/jobs/cloud-engineer-92543', '2026-01-25', 'Emily Brown', 'emily.brown@microsoft.com', '+1-258-321-1838', 'Screening', NULL, NULL, NULL, 'Remote work available with flexible hours. Team uses modern tech stack including React and Node.js.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(13, 4, NULL, 'Tesla', 'Software Developer', 'https://careers.tesla.com/jobs/software-developer-85424', '2026-01-18', 'Ryan Cooper', 'ryan.cooper@tesla.com', '+1-921-535-1074', 'Screening', NULL, NULL, NULL, 'Challenging technical interview process with 4 rounds. Preparing data structures and algorithms.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(14, 5, NULL, 'Spotify', 'Backend Engineer', 'https://careers.spotify.com/jobs/backend-engineer-44368', '2026-01-12', 'Emma Wilson', 'emma.wilson@spotify.com', '+1-232-633-6309', 'Applied', NULL, NULL, NULL, 'Opportunity for career growth and learning. Mentorship program available for junior developers.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(15, 5, NULL, 'Tesla', 'Embedded Engineer', 'https://careers.tesla.com/jobs/embedded-engineer-18128', '2025-12-27', 'Sophia Martinez', 'sophia.martinez@tesla.com', '+1-601-800-9095', 'Offer', NULL, NULL, NULL, 'Recruiter was very responsive and helpful. Company has great Glassdoor reviews.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(16, 5, NULL, 'Microsoft', 'DevOps Engineer', 'https://careers.microsoft.com/jobs/devops-engineer-20111', '2026-01-28', 'David Wilson', 'david.wilson@microsoft.com', '+1-549-426-7255', 'Interview', NULL, NULL, NULL, 'Recruiter was very responsive and helpful. Company has great Glassdoor reviews.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(17, 5, NULL, 'Netflix', 'Senior Engineer', 'https://careers.netflix.com/jobs/senior-engineer-50555', '2026-01-22', 'Chris Johnson', 'chris.johnson@netflix.com', '+1-649-324-3576', 'Rejected', NULL, NULL, NULL, 'Competitive salary range $120k-$150k, excellent benefits package including health, dental, and 401k.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(18, 6, NULL, 'Meta', 'Product Engineer', 'https://careers.meta.com/jobs/product-engineer-46069', '2026-01-22', 'Maria Garcia', 'maria.garcia@meta.com', '+1-862-707-6483', 'Applied', NULL, NULL, NULL, 'Recruiter was very responsive and helpful. Company has great Glassdoor reviews.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(19, 6, NULL, 'Google', 'Software Engineer', 'https://careers.google.com/jobs/software-engineer-26234', '2026-01-22', 'Jennifer Lee', 'jennifer.lee@google.com', '+1-640-799-9830', 'Rejected', NULL, NULL, NULL, 'Strong focus on work-life balance. Company offers unlimited PTO and professional development budget.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(20, 6, NULL, 'Salesforce', 'Full Stack Engineer', 'https://careers.salesforce.com/jobs/full-stack-engineer-61836', '2026-02-15', 'Jacob Walker', 'jacob.walker@salesforce.com', '+1-550-802-9869', 'Screening', NULL, NULL, NULL, 'Team seems very collaborative and innovative. Strong emphasis on code quality and best practices.', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(21, 2, 1, 'Google', 'Senior Software Engineer', 'https://careers.google.com/jobs/senior-software-engineer-12345', '2026-02-21', 'Sarah Chen', 'sarah.chen@google.com', '+1-650-555-0100', 'Applied', NULL, NULL, NULL, '', '2026-02-21 12:52:59', '2026-02-21 12:52:59'),
(22, 1, 1, 'Google', 'Senior Software Engineer', 'https://careers.google.com/jobs/senior-software-engineer-12345', '2026-02-21', 'Sarah Chen', 'sarah.chen@google.com', '+1-650-555-0100', 'Applied', NULL, NULL, NULL, '', '2026-02-21 13:33:07', '2026-02-21 13:33:07');

-- --------------------------------------------------------

--
-- Table structure for table `job_documents`
--

DROP TABLE IF EXISTS `job_documents`;
CREATE TABLE IF NOT EXISTS `job_documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `job_posting_id` int NOT NULL,
  `document_type` enum('Job Description','Company Info','Other') COLLATE utf8mb4_general_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `file_size` int NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `job_posting_id` (`job_posting_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_postings`
--

DROP TABLE IF EXISTS `job_postings`;
CREATE TABLE IF NOT EXISTS `job_postings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `position` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `job_link` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `requirements` text COLLATE utf8mb4_general_ci,
  `salary_range` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `location` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `job_type` enum('Full-time','Part-time','Contract','Internship') COLLATE utf8mb4_general_ci DEFAULT 'Full-time',
  `contact_person` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `posted_by` int NOT NULL,
  `status` enum('Active','Closed','Draft') COLLATE utf8mb4_general_ci DEFAULT 'Active',
  `posted_date` date NOT NULL,
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `posted_by` (`posted_by`),
  KEY `idx_job_posting_status` (`status`),
  KEY `idx_job_posting_date` (`posted_date`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_postings`
--

INSERT INTO `job_postings` (`id`, `company_name`, `position`, `job_link`, `description`, `requirements`, `salary_range`, `location`, `job_type`, `contact_person`, `contact_email`, `contact_phone`, `posted_by`, `status`, `posted_date`, `deadline`, `created_at`, `updated_at`) VALUES
(1, 'Google', 'Senior Software Engineer', 'https://careers.google.com/jobs/senior-software-engineer-12345', 'We are looking for an experienced Software Engineer to join our team. You will be working on cutting-edge technologies and solving complex problems at scale.', '• 5+ years of software development experience\\n• Strong knowledge of Java, Python, or Go\\n• Experience with distributed systems\\n• BS/MS in Computer Science or related field', '$150,000 - $200,000', 'Mountain View, CA', 'Full-time', 'Sarah Chen', 'sarah.chen@google.com', '+1-650-555-0100', 1, 'Active', '2026-02-21', '2026-03-23', '2026-02-21 12:38:09', '2026-02-21 12:38:09'),
(2, 'Microsoft', 'Cloud Solutions Architect', 'https://careers.microsoft.com/jobs/cloud-architect-67890', 'Join our Azure team to design and implement cloud solutions for enterprise clients. Help organizations transform their infrastructure and applications.', '• 7+ years in cloud architecture\\n• Azure/AWS certifications preferred\\n• Strong communication skills\\n• Experience with microservices and containers', '$140,000 - $180,000', 'Redmond, WA', 'Full-time', 'Michael Roberts', 'michael.roberts@microsoft.com', '+1-425-555-0200', 1, 'Active', '2026-02-21', '2026-03-23', '2026-02-21 12:38:09', '2026-02-21 12:38:09'),
(3, 'Amazon', 'Data Engineer', 'https://careers.amazon.com/jobs/data-engineer-54321', 'Build and maintain data pipelines that power Amazon\'s analytics and machine learning systems. Work with petabytes of data daily.', '• 3+ years in data engineering\\n• Proficiency in SQL, Python, Spark\\n• Experience with AWS services (S3, EMR, Redshift)\\n• Strong problem-solving skills', '$130,000 - $170,000', 'Seattle, WA', 'Full-time', 'Lisa Anderson', 'lisa.anderson@amazon.com', '+1-206-555-0300', 1, 'Active', '2026-02-21', '2026-03-23', '2026-02-21 12:38:09', '2026-02-21 12:38:09'),
(4, 'Meta', 'Frontend Developer', 'https://careers.meta.com/jobs/frontend-developer-98765', 'Create amazing user experiences for billions of users. Work with React, GraphQL, and modern web technologies.', '• 4+ years of frontend development\\n• Expert in React, TypeScript, CSS\\n• Experience with performance optimization\\n• Portfolio of web applications', '$135,000 - $175,000', 'Menlo Park, CA', 'Full-time', 'Kevin Zhang', 'kevin.zhang@meta.com', '+1-650-555-0400', 1, 'Active', '2026-02-21', '2026-03-23', '2026-02-21 12:38:09', '2026-02-21 12:38:09'),
(5, 'Apple', 'iOS Developer', 'https://careers.apple.com/jobs/ios-developer-11223', 'Develop innovative features for iOS applications used by millions. Join a team passionate about creating the best user experience.', '• 5+ years iOS development experience\\n• Expert in Swift and SwiftUI\\n• Published apps in App Store\\n• Strong understanding of iOS frameworks', '$145,000 - $190,000', 'Cupertino, CA', 'Full-time', 'Jessica Kim', 'jessica.kim@apple.com', '+1-408-555-0500', 1, 'Active', '2026-02-21', '2026-03-23', '2026-02-21 12:38:10', '2026-02-21 12:38:10'),
(6, 'Netflix', 'Machine Learning Engineer', 'https://careers.netflix.com/jobs/ml-engineer-44556', 'Build recommendation systems and personalization algorithms that delight our 200M+ subscribers worldwide.', '• MS/PhD in CS, ML, or related field\\n• 3+ years in machine learning\\n• Experience with TensorFlow/PyTorch\\n• Strong Python and Scala skills', '$160,000 - $220,000', 'Los Gatos, CA', 'Full-time', 'Chris Johnson', 'chris.johnson@netflix.com', '+1-408-555-0600', 1, 'Active', '2026-02-21', '2026-03-23', '2026-02-21 12:38:10', '2026-02-21 12:38:10'),
(7, 'Tesla', 'Embedded Software Engineer', 'https://careers.tesla.com/jobs/embedded-engineer-77889', 'Develop software for Tesla vehicles and energy products. Work on autonomous driving, battery management, and vehicle control systems.', '• 4+ years embedded systems experience\\n• Proficiency in C/C++\\n• Experience with RTOS and hardware interfaces\\n• Automotive experience preferred', '$125,000 - $165,000', 'Palo Alto, CA', 'Full-time', 'Alex Turner', 'alex.turner@tesla.com', '+1-650-555-0700', 1, 'Active', '2026-02-21', '2026-03-23', '2026-02-21 12:38:10', '2026-02-21 12:38:10'),
(8, 'Spotify', 'Backend Engineer', 'https://careers.spotify.com/jobs/backend-engineer-33445', 'Build scalable backend services that power music streaming for 400M+ users. Work with microservices, Kubernetes, and cloud infrastructure.', '• 3+ years backend development\\n• Strong Java or Python skills\\n• Experience with distributed systems\\n• Knowledge of databases and caching', '$120,000 - $160,000', 'New York, NY', 'Full-time', 'Emma Wilson', 'emma.wilson@spotify.com', '+1-212-555-0800', 1, 'Active', '2026-02-21', '2026-03-23', '2026-02-21 12:38:10', '2026-02-21 12:38:10'),
(9, 'Adobe', 'UX Designer', 'https://careers.adobe.com/jobs/ux-designer-66778', 'Design intuitive and beautiful user experiences for Creative Cloud applications. Collaborate with product managers and engineers.', '• 5+ years UX design experience\\n• Portfolio showcasing design work\\n• Proficiency in Figma, Adobe XD\\n• Strong understanding of design systems', '$110,000 - $150,000', 'San Jose, CA', 'Full-time', 'Nathan Scott', 'nathan.scott@adobe.com', '+1-408-555-0900', 1, 'Active', '2026-02-21', '2026-03-23', '2026-02-21 12:38:10', '2026-02-21 12:38:10'),
(10, 'Salesforce', 'DevOps Engineer', 'https://careers.salesforce.com/jobs/devops-engineer-99001', 'Automate infrastructure, improve deployment pipelines, and ensure high availability of Salesforce services.', '• 4+ years DevOps experience\\n• Expert in Docker, Kubernetes, Terraform\\n• Strong scripting skills (Python, Bash)\\n• Experience with CI/CD tools', '$130,000 - $170,000', 'San Francisco, CA', 'Full-time', 'Mia Robinson', 'mia.robinson@salesforce.com', '+1-415-555-1000', 1, 'Active', '2026-02-21', '2026-03-23', '2026-02-21 12:38:10', '2026-02-21 12:38:10');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

DROP TABLE IF EXISTS `reminders`;
CREATE TABLE IF NOT EXISTS `reminders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `application_id` int NOT NULL,
  `reminder_type` enum('Interview','Deadline','Follow-up','Other') COLLATE utf8mb4_general_ci NOT NULL,
  `reminder_date` datetime NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `is_sent` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  KEY `idx_reminder_date` (`reminder_date`,`is_sent`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `application_id`, `reminder_type`, `reminder_date`, `description`, `is_sent`, `created_at`) VALUES
(1, 6, 'Interview', '2026-02-23 12:30:29', 'Technical interview scheduled - prepare coding questions', 0, '2026-02-21 12:30:29'),
(2, 9, 'Interview', '2026-02-27 12:30:29', 'Technical interview scheduled - prepare coding questions', 0, '2026-02-21 12:30:29'),
(3, 16, 'Interview', '2026-03-03 12:30:29', 'Technical interview scheduled - prepare coding questions', 0, '2026-02-21 12:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','job_seeker') COLLATE utf8mb4_general_ci DEFAULT 'job_seeker',
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `career_preferences` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_user_email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `role`, `password`, `phone`, `career_preferences`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@jobtracker.com', 'admin', '$2y$10$RtYsN4526q/nH525MIHBCOQdDcZK/vmTlPi7QsZQbgX/t5pjVY.5u', '+1-555-0100', 'System Administrator', '2026-02-21 12:30:28', '2026-02-21 12:35:39'),
(2, 'John Smith', 'john.smith@email.com', 'job_seeker', '$2y$10$f19YNLcBRTn5NSwxU61z/e5xbGujjFusgqHm6eJECmYYw0FwC1q4W', '+1-555-0101', 'Software Development, Full Stack', '2026-02-21 12:30:28', '2026-02-21 12:30:28'),
(3, 'Sarah Johnson', 'sarah.johnson@email.com', 'job_seeker', '$2y$10$1a9VeHP1t8Okvd31GSPzweFSM.Kn4AESx.xjPFWJH1Dw7srB/94t.', '+1-555-0102', 'Data Science, Machine Learning', '2026-02-21 12:30:28', '2026-02-21 12:30:28'),
(4, 'Michael Chen', 'michael.chen@email.com', 'job_seeker', '$2y$10$TQ5xBQzgZgtoNNV.S/DMwOcDwtQFRXttNFD3CKPTg5rV28YPEorVm', '+1-555-0103', 'DevOps, Cloud Engineering', '2026-02-21 12:30:28', '2026-02-21 12:30:28'),
(5, 'Emily Davis', 'emily.davis@email.com', 'job_seeker', '$2y$10$sMJznz0siRMJOnKpGufOve7STbPBUK2gW2aS.KKtOD.WTgW0hYlle', '+1-555-0104', 'UI/UX Design, Frontend Development', '2026-02-21 12:30:29', '2026-02-21 12:30:29'),
(6, 'David Martinez', 'david.martinez@email.com', 'job_seeker', '$2y$10$8OzxxjMFpVp43WX2MfxcWOzSffCgFYebFKlFi4QlzOt7klkIeakEG', '+1-555-0105', 'Project Management, Agile', '2026-02-21 12:30:29', '2026-02-21 12:30:29');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
