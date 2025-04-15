<?php
/*
* Database connection file
* VULNERABILITY: Error messages reveal database details
*/

// Using SQLite instead of MySQL for easier setup on Replit
$database = "vulnerable_db.sqlite";

// Create a simple SQLite connection - we keep it deliberately vulnerable
try {
    // VULNERABILITY: This will create the database file in a public directory
    $conn = new SQLite3($database);
} catch (Exception $e) {
    // VULNERABILITY: This will show database errors to users
    die("Connection failed: " . $e->getMessage());
}

// Add some basic MySQL compatibility functions to our database for this demo
// In a real application, you would use proper prepared statements

// Create global variables for DB errors and insert ID
$GLOBALS['db_error'] = '';
$GLOBALS['db_last_insert_id'] = 0;

// Query wrapper function for both SELECT and UPDATE/INSERT/DELETE
function db_query($sql) {
    global $conn;
    
    try {
        // Check if this is a SELECT query or other type
        if (stripos(trim($sql), 'SELECT') === 0) {
            $result = $conn->query($sql);
            if (!$result) {
                $GLOBALS['db_error'] = $conn->lastErrorMsg();
                return false;
            }
            return $result;
        } else {
            // For non-SELECT queries (INSERT, UPDATE, DELETE)
            $result = $conn->exec($sql);
            if ($result === false) {
                $GLOBALS['db_error'] = $conn->lastErrorMsg();
                return false;
            }
            
            // For INSERT queries, store the last insert ID
            if (stripos(trim($sql), 'INSERT') === 0) {
                $GLOBALS['db_last_insert_id'] = $conn->lastInsertRowID();
            }
            
            return true;
        }
    } catch (Exception $e) {
        $GLOBALS['db_error'] = $e->getMessage();
        return false;
    }
}

// Simpler compatibility function to replace result->num_rows
function db_has_rows($result) {
    return ($result && $result->fetchArray() ? true : false);
}

// We keep the query function in the global scope for this deliberately insecure app
$conn->query = function($sql) {
    return db_query($sql);
};
?>
