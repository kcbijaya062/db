<?php
session_start();

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    die('Failed to connect to database');
}

$sql = "SELECT password, is_admin FROM UserAccountData WHERE username = :username";
$stmt = oci_parse($connection, $sql);
oci_bind_by_name($stmt, ":username", $username);
oci_execute($stmt);
$row = oci_fetch_array($stmt, OCI_ASSOC);

if ($row) {
    if ($password == $row['PASSWORD']) {
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = ($row['IS_ADMIN'] == 'Y') ? true : false;

        // Check if the user is also a student admin
        $studentAdminSql = "SELECT start_date FROM StudentAdminUserData WHERE username = :username";
        $stmtAdmin = oci_parse($connection, $studentAdminSql);
        oci_bind_by_name($stmtAdmin, ":username", $username);
        oci_execute($stmtAdmin);
        $rowAdmin = oci_fetch_array($stmtAdmin, OCI_ASSOC);
        
        if ($rowAdmin || $_SESSION['is_admin']) {
            // The user is a student admin or an admin, redirect accordingly
            header("Location: student_admin_home.php");
            exit;
        } else {
            die('Not authorized as student admin or admin. <a href="login.php">Go back</a>');
        }
    } else {
        die('Invalid password. <a href="login.php">Go back</a>');
    }
} else {
    die('Username not found. <a href="login.php">Go back</a>');
}

oci_free_statement($stmt);
oci_close($connection);
?>
