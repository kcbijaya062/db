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
    if ($password == $row['PASSWORD'] && $row['IS_ADMIN'] == 'Y') {
        $_SESSION['admin_username'] = $username;
        header("Location: adminhome.php");
        exit;
    } else {
        die('Invalid login credentials or not an admin. <a href="adminlogin.php">Go back</a>');
    }
} else {
    die('Username not found. <a href="adminlogin.php">Go back</a>');
}

oci_free_statement($stmt);
oci_close($connection);
?>
