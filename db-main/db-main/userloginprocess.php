<?php
// Start or resume a session
session_start();

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Connect to the database
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    die('Failed to connect to database');
}

// Prepare SQL statement for safe execution
$sql = "SELECT password FROM UserAccountData WHERE username = :username";
$stmt = oci_parse($connection, $sql);
oci_bind_by_name($stmt, ":username", $username);
oci_execute($stmt);
$row = oci_fetch_array($stmt, OCI_ASSOC);

// If a row is returned, the username exists
if ($row) {
    if ($password == $row['PASSWORD']) {
        // Authentication successful
        $_SESSION['username'] = $username; // Store username in session
        header("Location: user_home.php"); // Redirect to user home
        exit;
    } else {
        die('Invalid password. <a href="login.php">Go back</a>');
    }
} else {
    die('Username not found. <a href="login.php">Go back</a>');
}

oci_free_statement($stmt);
oci_close($connection);
?>
