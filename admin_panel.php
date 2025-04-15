<?php
session_start();
include 'db_connect.php';

// VULNERABILITY: No authentication check for admin panel
// A proper secure implementation would check for admin privileges

$users = [];
$query = "SELECT * FROM users";
$result = db_query($query);

if ($result) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $users[] = $row;
    }
}

// Handle user deletion
if (isset($_GET['delete_user']) && !empty($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    $delete_query = "DELETE FROM users WHERE id = $user_id";
    
    if (db_query($delete_query)) {
        $message = "<span class='success'>User deleted successfully!</span>";
        // Refresh the users list
        header("Location: admin_panel.php");
        exit;
    } else {
        $message = "<span class='error'>Error deleting user: " . $GLOBALS['db_error'] . "</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Vulnerable Web App</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table, th, td {
            border: 1px solid #ddd;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .action-links a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }
        
        .action-links a.delete {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        
        <div class="vulnerability-note">
            <h3>Vulnerability: No Authentication</h3>
            <p>This admin panel is accessible to anyone without authentication. It should be restricted to admin users only.</p>
            <p>Additionally, the user management actions are not protected against CSRF attacks.</p>
        </div>
        
        <div class="form-container" style="max-width: 800px;">
            <h2>User Management</h2>
            
            <?php if (isset($message)): ?>
                <div class="result">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Admin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <!-- VULNERABILITY: Displaying passwords in plaintext -->
                            <td><?php echo $user['password']; ?></td>
                            <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                            <td class="action-links">
                                <!-- VULNERABILITY: No CSRF token protection -->
                                <a href="admin_panel.php?delete_user=<?php echo $user['id']; ?>" class="delete" 
                                   onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
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
