# Deliberately Vulnerable Web Application

## ⚠️ WARNING ⚠️

This web application is **DELIBERATELY VULNERABLE** and contains numerous security flaws. It is designed for educational purposes only, to demonstrate common web security vulnerabilities.

**DO NOT:**
- Deploy this application on a public server
- Use any real credentials or personal information
- Use this code in production environments
- Leave this application running unattended

**USE ONLY:**
- In isolated, controlled environments
- For educational and training purposes
- To learn about web security vulnerabilities

## Implemented Vulnerabilities

1. **SQL Injection (login.php)**
   - The login form is vulnerable to SQL injection
   - Try using `' OR '1'='1` as the username

2. **Reflected XSS (search.php)**
   - The search form outputs unsanitized user input
   - Try searching for `<script>alert('XSS')</script>`

3. **Unrestricted File Upload (upload.php)**
   - The file upload form accepts any file type without validation
   - Try uploading a PHP file for remote code execution

4. **No Input Validation (update_profile.php)**
   - User input is not validated or sanitized
   - Try submitting malicious input in the profile form

5. **Hardcoded Admin Credentials (setup.php)**
   - Admin credentials are hardcoded in the setup script
   - Username: `admin` Password: `admin123`

6. **No Session Management (login.php, logout.php)**
   - Improper session handling after login
   - Session not properly destroyed on logout

7. **Unauthenticated Admin Panel (admin_panel.php)**
   - Admin panel accessible without authentication
   - Can view and manipulate all user accounts

8. **No CSRF Protection (update_profile.php, admin_panel.php)**
   - Forms do not include CSRF tokens
   - Vulnerable to cross-site request forgery attacks

9. **Information Disclosure (error_page.php)**
   - Verbose error messages reveal internal information
   - Server details and file paths are exposed

10. **Open Redirect (redirect.php)**
    - Redirects to any URL without validation
    - Can be used for phishing attacks

## Setup Instructions

1. Run `setup.php` to initialize the database
2. The application will set up a MySQL database with sample data
3. Default admin account: `admin` / `admin123`
4. Default user account: `user` / `password`

## Educational Notes

Each page includes educational notes explaining the vulnerability and how it can be exploited. This application is intended to help security professionals, developers, and students understand common web security issues and how to properly address them in real applications.

## Disclaimer

The creators of this application are not responsible for any misuse or damage caused by this code. This application is provided for educational purposes only.
