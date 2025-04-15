<?php
session_start();
include 'db_connect.php';

$message = '';

// Get user info if logged in
$userInfo = [];
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = db_query($query);
    
    if ($result) {
        $userInfo = $result->fetchArray(SQLITE3_ASSOC);
    }
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['profile_info'])) {
        // VULNERABILITY: No input validation or sanitization
        $email = $_POST['email'];
        $profile = $_POST['profile_info'];
        
        // VULNERABILITY: SQL Injection in update query
        if (isset($userInfo['id'])) {
            $user_id = $userInfo['id'];
            $query = "UPDATE users SET email = '$email', profile_info = '$profile' WHERE id = $user_id";
            
            if (db_query($query)) {
                $message = "<span class='success'>Profile updated successfully!</span>";
                
                // Update the user info
                $query = "SELECT * FROM users WHERE id = $user_id";
                $result = db_query($query);
                
                if ($result) {
                    $userInfo = $result->fetchArray(SQLITE3_ASSOC);
                }
            } else {
                $message = "<span class='error'>Error updating profile: " . $GLOBALS['db_error'] . "</span>";
            }
        } else {
            $message = "<span class='error'>You must be logged in to update your profile.</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Vulnerable Web App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Update Profile</h1>
        
        <div class="vulnerability-note">
            <h3>Vulnerabilities:</h3>
            <ul>
                <li>No Input Validation: This form doesn't validate or sanitize user input</li>
                <li>No CSRF Protection: This form doesn't include a CSRF token</li>
                <li>Potential SQL Injection: User input is directly inserted into SQL queries</li>
                <li>Stored XSS: Profile info can contain malicious scripts that will be stored and executed</li>
            </ul>
            <p>Try submitting HTML or JavaScript in the profile information. Example: <code>&lt;script&gt;alert('Stored XSS')&lt;/script&gt;</code></p>
        </div>
        
        <div class="form-container">
            <?php if (empty($userInfo)): ?>
                <div class="error">
                    <p>You must be <a href="login.php">logged in</a> to update your profile.</p>
                </div>
            <?php else: ?>
                <!-- VULNERABILITY: No CSRF Token -->
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" value="<?php echo $userInfo['username']; ?>" disabled>
                        <small>Username cannot be changed</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $userInfo['email']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_info">Profile Information:</label>
                        <textarea id="profile_info" name="profile_info"><?php echo $userInfo['profile_info']; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="button">Update Profile</button>
                    </div>
                </form>
                
                <?php if (!empty($message)): ?>
                    <div class="result">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <div class="result">
                    <h3>Current Profile:</h3>
                    <!-- VULNERABILITY: Stored XSS - Outputting unsanitized user input -->
                    <div>
                        <?php echo $userInfo['profile_info']; ?>
                    </div>
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
