# Job Application Tracker

A professional web-based application to help job seekers organize, monitor, and manage their job applications efficiently.

## Features

- **User Management**: Registration, login, and profile management with JWT authentication
- **Job Application Management**: Add, update, delete, and track applications with multiple statuses
- **Dashboard & Analytics**: Visual statistics and insights on your job search progress
- **Reminders & Notifications**: Email notifications for interviews, deadlines, and follow-ups
- **Document Management**: Upload and manage resumes, cover letters, and job descriptions
- **Search & Filter**: Find applications quickly by keywords, date, status, or company

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, Tailwind CSS, JavaScript
- **Authentication**: JWT (JSON Web Tokens)
- **Email**: SendGrid API
- **Server**: WAMP/XAMPP (Apache)

## Installation

### Prerequisites
- WAMP Server or XAMPP installed
- PHP 7.4 or higher
- MySQL 5.7 or higher
- SendGrid account (for email notifications)

### Setup Steps

1. **Clone or extract the project** to your WAMP www directory:
   ```
   D:\wamp64\www\jobportal\
   ```

2. **Create the database**:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the database schema from `database/schema.sql`
   - Or run the SQL commands directly

3. **Configure the application**:
   - Edit `config/config.php`:
     - Update `JWT_SECRET_KEY` with a secure random string
     - Add your SendGrid API key to `SENDGRID_API_KEY`
     - Update `FROM_EMAIL` with your sender email
   
   - Edit `config/database.php` if needed (default settings work with WAMP):
     ```php
     private $host = "localhost";
     private $db_name = "db_jobtracker";
     private $username = "root";
     private $password = "";
     ```

4. **Create uploads directory**:
   ```
   mkdir uploads
   ```
   Ensure the directory has write permissions.

5. **Access the application**:
   - Open your browser and navigate to: `http://localhost/jobportal/`

## Usage

### User Registration & Login
1. Open the application in your browser
2. Click "Register" to create a new account
3. Fill in your details and submit
4. Login with your credentials

### Managing Applications
1. Click "Add Application" to create a new job application entry
2. Fill in company details, position, date applied, and contact information
3. Track status: Applied → Screening → Interview → Offer/Rejected
4. Edit or delete applications as needed

### Dashboard
- View statistics: total applications, status breakdown
- Filter by status or search by keywords
- See all your applications in a organized table

### Setting Reminders
- Use the API endpoint `/api/reminders/create.php` to set reminders
- Configure a cron job to run `cron/send_reminders.php` daily for email notifications

### Document Upload
- Use the API endpoint `/api/documents/upload.php` to upload documents
- Supported formats: PDF, DOC, DOCX, TXT
- Maximum file size: 5MB

## API Endpoints

### Authentication
- `POST /api/auth/register.php` - Register new user
- `POST /api/auth/login.php` - User login

### Applications
- `POST /api/applications/create.php` - Create application
- `GET /api/applications/list.php` - List applications
- `PUT /api/applications/update.php` - Update application
- `DELETE /api/applications/delete.php?id={id}` - Delete application

### Dashboard
- `GET /api/dashboard/stats.php` - Get statistics

### Reminders
- `POST /api/reminders/create.php` - Create reminder

### Documents
- `POST /api/documents/upload.php` - Upload document

## Cron Job Setup

To enable automatic email reminders, set up a cron job:

**Windows (Task Scheduler)**:
1. Open Task Scheduler
2. Create a new task
3. Set trigger: Daily at your preferred time
4. Action: Start a program
5. Program: `php.exe`
6. Arguments: `D:\wamp64\www\jobportal\cron\send_reminders.php`

**Linux/Mac**:
```bash
0 9 * * * php /path/to/jobportal/cron/send_reminders.php
```

## Security Notes

1. Change the `JWT_SECRET_KEY` in production
2. Use HTTPS in production environment
3. Keep your SendGrid API key secure
4. Regularly update PHP and dependencies
5. Implement rate limiting for API endpoints
6. Validate and sanitize all user inputs

## Troubleshooting

**Database connection error**:
- Verify MySQL is running in WAMP
- Check database credentials in `config/database.php`
- Ensure database `db_jobtracker` exists

**JWT token errors**:
- Clear browser localStorage
- Check if token is being sent in Authorization header
- Verify JWT_SECRET_KEY is set correctly

**File upload issues**:
- Check `uploads/` directory exists and has write permissions
- Verify file size is under 5MB
- Ensure file extension is allowed

## License

This project is created for educational purposes.

## Support

For issues and questions, please refer to the project documentation or contact the development team.
