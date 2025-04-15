<?php
session_start();

// VULNERABILITY: Improper session destruction
// Just unset the username without proper session handling
if (isset($_SESSION['username'])) {
    unset($_SESSION['username']);
    unset($_SESSION['is_admin']);
}

// Redirect to home page
header("Location: index.php");
exit;
?>
