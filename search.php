<?php
session_start();
include 'db_connect.php';

$searchResults = [];
$searchTerm = '';

if (isset($_GET['term'])) {
    // Get search term from URL
    $searchTerm = $_GET['term'];
    
    // VULNERABILITY: SQL Injection in search
    $query = "SELECT * FROM posts WHERE title LIKE '%$searchTerm%' OR content LIKE '%$searchTerm%'";
    
    $result = db_query($query);
    
    if ($result) {
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $searchResults[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Vulnerable Web App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Search Posts</h1>
        
        <div class="vulnerability-note">
            <h3>Vulnerability: Reflected XSS</h3>
            <p>This search form is vulnerable to reflected XSS. Try searching for: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></p>
            <p>Additionally, the search is also vulnerable to SQL injection. Try: <code>' UNION SELECT username, password, email, id, created_at FROM users; --</code></p>
        </div>
        
        <div class="form-container">
            <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="term">Search Term:</label>
                    <input type="text" id="term" name="term" value="<?php echo $searchTerm; ?>" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="button">Search</button>
                </div>
            </form>
            
            <!-- VULNERABILITY: Reflected XSS - directly outputting unsanitized user input -->
            <?php if (!empty($searchTerm)): ?>
                <div class="result">
                    <h3>Search Results for: <?php echo $searchTerm; ?></h3>
                    
                    <?php if (empty($searchResults)): ?>
                        <p>No results found for your search.</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($searchResults as $result): ?>
                                <li>
                                    <h4><?php echo $result['title']; ?></h4>
                                    <p><?php echo $result['content']; ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
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
