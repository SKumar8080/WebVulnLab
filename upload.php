<?php
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // VULNERABILITY: No file type validation or restrictions
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $uploadDir = 'uploaded/';
        
        // VULNERABILITY: Using original filename without validation
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);
        
        // VULNERABILITY: No file type checking or size restrictions
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $message = "<span class='success'>File uploaded successfully to: $uploadFile</span>";
            
            // VULNERABILITY: Displaying direct path to uploaded file
            $message .= "<br><a href='$uploadFile' target='_blank'>View Uploaded File</a>";
        } else {
            $message = "<span class='error'>File upload failed</span>";
        }
    } else {
        $message = "<span class='error'>Please select a file to upload</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload - Vulnerable Web App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>File Upload</h1>
        
        <div class="vulnerability-note">
            <h3>Vulnerability: Unrestricted File Upload</h3>
            <p>This form allows uploading any file type without validation. You can upload PHP files that will be executed by the server.</p>
            <p>Example: Create a file named <code>shell.php</code> with the content <code>&lt;?php system($_GET['cmd']); ?&gt;</code> and upload it. Then access it with <code>/uploaded/shell.php?cmd=ls</code> to execute commands.</p>
        </div>
        
        <div class="form-container">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">Select File to Upload:</label>
                    <input type="file" id="file" name="file" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="button">Upload File</button>
                </div>
            </form>
            
            <?php if (!empty($message)): ?>
                <div class="result">
                    <?php echo $message; ?>
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
