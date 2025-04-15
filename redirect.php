<?php
// VULNERABILITY: Open Redirect

// Get the URL from the query string
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    
    // VULNERABILITY: No validation of the URL
    // This allows redirecting to any external site
    header("Location: $url");
    exit;
} else {
    $error = "No URL specified for redirection";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirect - Vulnerable Web App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>URL Redirector</h1>
        
        <div class="vulnerability-note">
            <h3>Vulnerability: Open Redirect</h3>
            <p>This page demonstrates an open redirect vulnerability, which can be used in phishing attacks.</p>
            <p>Try using the redirector to go to an external site: <a href="?url=https://example.com">Redirect to example.com</a></p>
            <p>A malicious attacker could use this to redirect users to phishing sites: <a href="?url=https://not-really-your-bank.com">Redirect to malicious site</a></p>
        </div>
        
        <div class="form-container">
            <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="url">Enter URL to redirect to:</label>
                    <input type="text" id="url" name="url" placeholder="https://example.com" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="button">Redirect</button>
                </div>
            </form>
            
            <?php if (isset($error)): ?>
                <div class="error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="nav-links">
                <a href="index.php">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
