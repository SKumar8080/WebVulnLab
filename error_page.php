<?php
// VULNERABILITY: Verbose error messages

// Simulate an error condition based on GET parameter
if (isset($_GET['error'])) {
    $error_type = $_GET['error'];
    
    // Simulate different errors based on the parameter
    switch ($error_type) {
        case '1':
            // Database error
            $error_message = "Error connecting to database: SQLSTATE[HY000] [1045] Access denied for user 'admin'@'localhost' (using password: YES)";
            $error_details = "Database connection failed at /var/www/html/db_connect.php:12 while trying to connect to 'vulnerable_db' on 'localhost'";
            $stack_trace = "Stack trace:\n#0 /var/www/html/error_page.php(15): include('/var/www/html/db_connect.php')\n#1 {main}";
            break;
            
        case '2':
            // File system error
            $error_message = "Warning: file_get_contents(/var/www/html/config/secret_keys.ini): failed to open stream: No such file or directory in /var/www/html/error_page.php on line 20";
            $error_details = "File system error while trying to read configuration";
            $stack_trace = "Stack trace:\n#0 /var/www/html/error_page.php(20): file_get_contents('/var/www/html/config/secret_keys.ini')\n#1 {main}";
            break;
            
        case '3':
            // PHP error
            $error_message = "Fatal error: Uncaught Error: Call to undefined function connect_to_api() in /var/www/html/error_page.php:25";
            $error_details = "The function connect_to_api() was called but is not defined";
            $stack_trace = "Stack trace:\n#0 {main}\n  thrown in /var/www/html/error_page.php on line 25";
            break;
            
        default:
            // Default error
            $error_message = "An unexpected error occurred";
            $error_details = "Error ID: " . md5(time());
            $stack_trace = "No stack trace available";
    }
    
    // VULNERABILITY: Configure PHP to display errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page - Vulnerable Web App</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-box {
            background-color: #ffebee;
            border: 1px solid #ffcdd2;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        
        .error-title {
            color: #d32f2f;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .error-details {
            font-family: monospace;
            background: #f5f5f5;
            padding: 10px;
            border: 1px solid #ddd;
            overflow-x: auto;
        }
        
        .stack-trace {
            margin-top: 15px;
            font-family: monospace;
            white-space: pre-wrap;
            background: #f5f5f5;
            padding: 10px;
            border: 1px solid #ddd;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Error Page</h1>
        
        <div class="vulnerability-note">
            <h3>Vulnerability: Information Disclosure</h3>
            <p>This page demonstrates verbose error messages that reveal sensitive information about the application's internal workings, file paths, and technology stack.</p>
            <p>Try accessing different error types: 
                <a href="?error=1">Database Error</a> | 
                <a href="?error=2">File System Error</a> | 
                <a href="?error=3">PHP Error</a>
            </p>
        </div>
        
        <div class="form-container">
            <?php if (isset($error_message)): ?>
                <div class="error-box">
                    <div class="error-title">Error Occurred</div>
                    <div class="error-details"><?php echo $error_message; ?></div>
                    
                    <?php if (isset($error_details)): ?>
                        <h4>Error Details:</h4>
                        <div class="error-details"><?php echo $error_details; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($stack_trace)): ?>
                        <h4>Stack Trace:</h4>
                        <div class="stack-trace"><?php echo $stack_trace; ?></div>
                    <?php endif; ?>
                    
                    <!-- VULNERABILITY: Additional system information disclosure -->
                    <h4>Server Information:</h4>
                    <div class="error-details">
                        <p>PHP Version: <?php echo phpversion(); ?></p>
                        <p>Server Software: <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
                        <p>Document Root: <?php echo $_SERVER['DOCUMENT_ROOT']; ?></p>
                        <p>Current User: <?php echo get_current_user(); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="result">
                    <p>No error selected. Please choose an error type from the links above.</p>
                </div>
            <?php endif; ?>
            
            <div class="nav-links">
                <a href="index.php">Back to Home</a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
