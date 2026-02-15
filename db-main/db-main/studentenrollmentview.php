<?php
// Establish connection with Oracle
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $message = 'Failed to connect to database: ' . oci_error()['message'];

}

// Create the view if it doesn't exist
$create_view_query = "CREATE OR REPLACE VIEW StudentEnrollments AS 
                      SELECT s.first_name, s.last_name, e.section_id, e.grade
                      FROM Students s
                      JOIN Enroll e ON s.student_id = e.student_id";
$create_view_stmt = oci_parse($connection, $create_view_query);

oci_execute($create_view_stmt);

// Fetch data from the view
$query = "SELECT * FROM StudentEnrollments";
$stmt = oci_parse($connection, $query);
oci_execute($stmt);

// Fetch data and store it in an array
$enrollments = array();
while ($row = oci_fetch_assoc($stmt)) {
    $enrollments[] = $row;
}

// Close the connection
oci_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollments</title>
</head>
<body>
    <h1>Student Enrollments</h1>
    <table border="1">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Section ID</th>
            <th>Grade</th>
        </tr>
        <?php foreach ($enrollments as $enrollment): ?>
            <tr>
                <td><?php echo $enrollment['FIRST_NAME']; ?></td>
                <td><?php echo $enrollment['LAST_NAME']; ?></td>
                <td><?php echo $enrollment['SECTION_ID']; ?></td>
                <td><?php echo $enrollment['GRADE']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="user_home.php" class="btn">Go back</a>
</body>
</html>
