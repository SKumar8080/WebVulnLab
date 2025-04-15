<?php
// Initialize success/error message
$message = "";

// Define database filename
$database = "vulnerable_db.sqlite";

try {
    // Create database connection
    $conn = new SQLite3($database);
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password TEXT NOT NULL,
        email TEXT,
        is_admin INTEGER DEFAULT 0,
        profile_info TEXT
    )";
    
    if (!$conn->exec($sql)) {
        throw new Exception("Error creating table: " . $conn->lastErrorMsg());
    }
    
    // Create posts table for demonstration
    $sql = "CREATE TABLE IF NOT EXISTS posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        content TEXT,
        user_id INTEGER,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!$conn->exec($sql)) {
        throw new Exception("Error creating posts table: " . $conn->lastErrorMsg());
    }
    
    // VULNERABILITY: Hardcoded admin credentials
    // Check if admin user exists
    $result = $conn->query("SELECT * FROM users WHERE username = 'admin'");
    $adminExists = false;
    
    if ($result) {
        $row = $result->fetchArray();
        $adminExists = !empty($row);
    }
    
    if (!$adminExists) {
        // Insert admin user
        $sql = "INSERT INTO users (username, password, email, is_admin) 
                VALUES ('admin', 'admin123', 'admin@example.com', 1)";
        
        if (!$conn->exec($sql)) {
            throw new Exception("Error inserting admin user: " . $conn->lastErrorMsg());
        }
        
        // Insert a regular user for testing
        $sql = "INSERT INTO users (username, password, email, is_admin, profile_info) 
                VALUES ('user', 'password', 'user@example.com', 0, 'Regular user account for testing')";
        
        if (!$conn->exec($sql)) {
            throw new Exception("Error inserting test user: " . $conn->lastErrorMsg());
        }
        
        // Insert sample posts
        $sql = "INSERT INTO posts (title, content, user_id) VALUES 
                ('First Post', 'This is the first test post content', 1),
                ('Welcome', 'Welcome to our vulnerable application', 1),
                ('User Guide', 'Learn how to use this application', 2)";
                
        if (!$conn->exec($sql)) {
            throw new Exception("Error inserting sample posts: " . $conn->lastErrorMsg());
        }
    }
    
    // Create uploads directory if it doesn't exist
    if (!file_exists('uploaded')) {
        mkdir('uploaded', 0777, true);
    }
    
    $message = "<span class='success'>Database setup completed successfully! Admin account created with username 'admin' and password 'admin123'.</span>";
    
} catch (Exception $e) {
    $message = "<span class='error'>Error: " . $e->getMessage() . "</span>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Database Setup</h1>
        
        <div class="form-container">
            <div class="vulnerability-note">
                <h3>Vulnerability Note:</h3>
                <p>This setup page includes hardcoded admin credentials and creates a database with users that have insecure passwords.</p>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="result">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="nav-links">
                <a href="index.php">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
