<?php
session_start();
include 'db_connect.php';

$error = '';
$success = '';

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // VULNERABILITY: SQL Injection 
    // Concatenating user input directly into SQL query
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Execute query
    $result = db_query($query);
    
    if ($result) {
        $user = $result->fetchArray(SQLITE3_ASSOC);
        if ($user) {
            // VULNERABILITY: No proper session management
            // Simply set a username in session without proper authentication tokens
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            $success = "Login successful! Welcome, " . $user['username'];
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Query failed: " . $GLOBALS['db_error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vulnerable Web App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Login Form</h1>
        
        <div class="vulnerability-note">
            <h3>Vulnerability: SQL Injection</h3>
            <p>This login form is vulnerable to SQL Injection. Try entering <code>' OR '1'='1</code> in the username field and anything in the password field.</p>
        </div>
        
        <div class="form-container">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="button">Login</button>
                </div>
            </form>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="nav-links">
                <a href="index.php">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
