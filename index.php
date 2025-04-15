<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable Web App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Vulnerable Web Application</h1>
        <div class="warning-box">
            <h2>⚠️ Educational Purpose Only ⚠️</h2>
            <p>This application is deliberately vulnerable and should only be used for educational purposes in a controlled environment.</p>
        </div>

        <div class="vulnerabilities-list">
            <h3>Implemented Vulnerabilities:</h3>
            <ol>
                <li><a href="login.php">SQL Injection (Login Form)</a></li>
                <li><a href="search.php">Reflected XSS (Search Form)</a></li>
                <li><a href="upload.php">Unrestricted File Upload</a></li>
                <li><a href="update_profile.php">No Input Validation & No CSRF Protection</a></li>
                <li><a href="admin_panel.php">Unauthenticated Admin Panel</a></li>
                <li><a href="error_page.php?error=1">Verbose Error Messages</a></li>
                <li><a href="redirect.php?url=https://example.com">Open Redirect</a></li>
            </ol>
        </div>

        <div class="setup-box">
            <h3>First-time Setup</h3>
            <p>If this is your first time running the application, please run the database setup:</p>
            <a href="setup.php" class="button">Setup Database</a>
        </div>
    </div>
</body>
</html>
