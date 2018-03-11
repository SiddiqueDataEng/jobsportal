# Installation Guide - Job Application Tracker

## Quick Start Guide

### Step 1: Database Setup

1. Start your WAMP server
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Click on "SQL" tab
4. Copy and paste the contents of `database/schema.sql`
5. Click "Go" to execute

The database `db_jobtracker` will be created with all necessary tables.

### Step 2: Configuration

1. **JWT Secret Key** (Important for security):
   - Open `config/config.php`
   - Change this line:
     ```php
     define('JWT_SECRET_KEY', 'your_secret_key_here_change_in_production');
     ```
   - Replace with a random string (at least 32 characters)

2. **SendGrid Email Setup** (Optional - for email notifications):
   - Sign up at https://sendgrid.com (free tier available)
   - Get your API key from SendGrid dashboard
   - Open `config/config.php`
   - Update:
     ```php
     define('SENDGRID_API_KEY', 'your_sendgrid_api_key_here');
     define('FROM_EMAIL', 'noreply@yourdomain.com');
     ```

3. **Database Connection** (Usually no changes needed):
   - Open `config/database.php`
   - Default settings work with WAMP:
     ```php
     private $host = "localhost";
     private $db_name = "db_jobtracker";
     private $username = "root";
     private $password = "";
     ```

### Step 3: Create Uploads Directory

Create a folder named `uploads` in the project root:
```
D:\wamp64\www\jobportal\uploads\
```

Right-click the folder → Properties → Security → Edit → Add write permissions for Users.

### Step 4: Access the Application

1. Make sure WAMP is running (green icon)
2. Open your browser
3. Navigate to: `http://localhost/jobportal/`
4. You should see the login/register page

### Step 5: Test the Application

1. Click "Register" tab
2. Create a test account:
   - Full Name: Test User
   - Email: test@example.com
   - Password: test123
3. Click "Register"
4. Login with your credentials
5. You should see the dashboard

## Troubleshooting

### Problem: "Connection error" message

**Solution**:
- Check if MySQL service is running in WAMP
- Verify database name is `db_jobtracker`
- Check credentials in `config/database.php`

### Problem: "404 Not Found" for API calls

**Solution**:
- Enable `mod_rewrite` in Apache:
  - Click WAMP icon → Apache → Apache modules → Check `rewrite_module`
- Restart WAMP server

### Problem: File upload fails

**Solution**:
- Create `uploads` folder if it doesn't exist
- Check folder permissions (should allow write access)
- Verify `upload_max_filesize` in php.ini (should be at least 5M)

### Problem: JWT token errors

**Solution**:
- Clear browser cache and localStorage
- Make sure `JWT_SECRET_KEY` is set in `config/config.php`
- Check browser console for detailed error messages

### Problem: Email notifications not working

**Solution**:
- Verify SendGrid API key is correct
- Check if `curl` extension is enabled in PHP
- Test SendGrid API key using their dashboard
- Email feature is optional - app works without it

## Optional: Email Reminders Setup

To enable automatic email reminders:

### Windows Task Scheduler:

1. Open Task Scheduler (search in Start menu)
2. Click "Create Basic Task"
3. Name: "Job Tracker Reminders"
4. Trigger: Daily
5. Time: 9:00 AM (or your preference)
6. Action: Start a program
7. Program/script: `C:\wamp64\bin\php\php7.4.9\php.exe` (adjust path to your PHP)
8. Add arguments: `D:\wamp64\www\jobportal\cron\send_reminders.php`
9. Finish

## Verification Checklist

- [ ] WAMP server is running
- [ ] Database `db_jobtracker` exists with tables
- [ ] Can access `http://localhost/jobportal/`
- [ ] Can register a new user
- [ ] Can login successfully
- [ ] Dashboard loads with statistics
- [ ] Can add a new job application
- [ ] Can edit and delete applications
- [ ] `uploads` folder exists

## Next Steps

After successful installation:

1. Change the JWT secret key to a secure random string
2. Set up SendGrid for email notifications (optional)
3. Configure cron job for automatic reminders (optional)
4. Start tracking your job applications!

## Support

If you encounter issues not covered here:
1. Check browser console for JavaScript errors (F12)
2. Check Apache error logs in WAMP
3. Verify all files are in correct locations
4. Ensure PHP version is 7.4 or higher

## Production Deployment Notes

When deploying to production:
- Use HTTPS (SSL certificate)
- Change JWT_SECRET_KEY to a strong random value
- Set proper file permissions
- Enable PHP error logging
- Use environment variables for sensitive data
- Implement rate limiting
- Regular database backups
