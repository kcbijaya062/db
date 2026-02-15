<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    die('Failed to connect to the database');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
    $username = $_SESSION['username'];
    $course_id = $_POST['course_id'];
    
    // Generate a unique enrollment ID
    $enrollment_id = uniqid('enroll_');
    
    $sql = "INSERT INTO Enrollments (enrollment_id, username, course_id) VALUES (:enrollment_id, :username, :course_id)";
    $stmt = oci_parse($connection, $sql);
    oci_bind_by_name($stmt, ':enrollment_id', $enrollment_id);
    oci_bind_by_name($stmt, ':username', $username);
    oci_bind_by_name($stmt, ':course_id', $course_id);
    
    if (oci_execute($stmt)) {
        echo "<p>Successfully enrolled in the course!</p>";
    } else {
        echo "<p>Failed to enroll in the course. Please try again.</p>";
    }
}

// Fetch all available courses
$sql = "SELECT course_id, course_name FROM Courses ORDER BY course_name";
$stmt = oci_parse($connection, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in a Course</title>
</head>
<body>
    <h1>Enroll in a Course</h1>
<p> computer science</p>
<p> computational science</p>
<p> Nursing</p>
<p> Lab Tecnhician</p>
    <form action="enroll_course.php" method="post">
        <label for="course_id">Select a Course:</label>
        <select name="course_id" id="course_id">
            <?php while ($row = oci_fetch_array($stmt, OCI_ASSOC)): ?>
                <option value="<?php echo htmlspecialchars($row['COURSE_ID']); ?>">
                    <?php echo htmlspecialchars($row['COURSE_NAME']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Enroll</button>
    </form>
</body>
</html>
<?php
oci_free_statement($stmt);
oci_close($connection);
?>
