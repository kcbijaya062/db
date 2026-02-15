<?php
//session_start();
if (!isset($_SESSION['username'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit;
}

// Database connection
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    die('Failed to connect to the database');
}

$sql = "SELECT course_id, course_name FROM Courses ORDER BY course_id";
$stmt = oci_parse($connection, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courses</title>
    <style>
        body { font-family: Arial, sans-serif; }
        ul { list-style-type: none; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Available Courses</h1>
    <p> Java</p>
    <p> C++</p>
    <p> c</p>
    <p> Javascript</p>
    <p> Ruby</p>
    <p> Cloud computing</p>
    <ul>
        <?php while ($row = oci_fetch_array($stmt, OCI_ASSOC)): ?>
            <li><strong><?php echo htmlspecialchars($row['COURSE_NAME']); ?></strong></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
<?php
oci_free_statement($stmt);
oci_close($connection);
?>
