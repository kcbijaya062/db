<?php
// Start the session if not already started
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve the username of the logged-in student
$username = $_SESSION['username'];

// Attempt to connect to the database
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");

// Check if the connection was successful
if (!$connection) {
    // Display error message and stop further execution
    $errorMessage = oci_error();
    echo "Failed to connect to database: " . $errorMessage['message'];
    exit();
}

// SQL command to get the academic information of the student
$query = "SELECT s.student_id, s.first_name, s.last_name, COUNT(e.section_id) AS num_courses_completed,
          SUM(c.credit_hours) AS total_credit_hours,
          SUM(c.credit_hours * DECODE(e.grade, 'A', 4, 'B', 3, 'C', 2, 'D', 1, 'F', 0)) /
          NULLIF(SUM(c.credit_hours), 0) AS gpa
          FROM Students s
          LEFT JOIN Enroll e ON s.student_id = e.student_id
          LEFT JOIN Sections sec ON e.section_id = sec.section_id
          LEFT JOIN Course c ON sec.course_number = c.course_number
          WHERE s.username = :username
          GROUP BY s.student_id, s.first_name, s.last_name";

echo "Student ID: " . $row['STUDENT_ID'];

// Parse the SQL command
$cursor = oci_parse($connection, $query);
if (!$cursor) {
    // For oci_parse errors, pass the connection handle
    $e = oci_error($connection);
    die($e['message']);
}

// Bind parameter here
oci_bind_by_name($cursor, ':username', $username);

// Execute the command
$result = oci_execute($cursor);
if (!$result) {
    // For oci_execute errors pass the cursor handle
    $e = oci_error($cursor);
    die($e['message']);
}

// Fetch academic information
$row = oci_fetch_assoc($cursor);

if ($row) {
    // Display academic information
    echo "<h2>Check here:</h2>";
    echo "<h2>Student Academic Information:</h2>";
    echo "<p>Student ID: {$row['STUDENT_ID']}</p>";
    echo "<p>First Name: {$row['FIRST_NAME']}</p>";
    echo "<p>Last Name: {$row['LAST_NAME']}</p>";
    echo "<p>Number of Courses Completed: {$row['NUM_COURSES_COMPLETED']}</p>";
    echo "<p>Total Credit Hours Earned: {$row['TOTAL_CREDIT_HOURS']}</p>";
    echo "<p>GPA: {$row['GPA']}</p>";
} else {
    echo "No academic information found for the logged-in student.";
}
// Close the cursor
oci_free_statement($cursor);
 
// SQL command to get all sections taken by the student
$querySections = "SELECT sec.section_id, sec.course_number, c.title, sec.semester_season, sec.semester_year,
                  c.credit_hours, e.grade
                  FROM Enroll e
                  JOIN Sections sec ON e.section_id = sec.section_id
                  JOIN Course c ON sec.course_number = c.course_number
                  WHERE e.student_id = :student_id";

// Parse the SQL command
$cursorSections = oci_parse($connection, $querySections);
if (!$cursorSections) {
    // For oci_parse errors, pass the connection handle
    $e = oci_error($connection);
    die($e['message']);
}

// Bind parameter here
oci_bind_by_name($cursorSections, ':student_id', $row['STUDENT_ID']);
echo "Student ID: " . $row['STUDENT_ID'];

// Execute the command
$resultSections = oci_execute($cursorSections);
if (!$resultSections) {
    // For oci_execute errors pass the cursor handle
    $e = oci_error($cursorSections);
    die($e['message']);
}

// Display sections taken by the student
echo "<h2>Sections Taken and section details here:</h2>";
echo "<table border='1'>";
echo "<tr><th>Section ID</th><th>Course Number</th><th>Course Title</th><th>Semester</th><th>Year</th><th>Credit Hours</th><th>Grade</th></tr>";
while ($rowSection = oci_fetch_assoc($cursorSections)) {
    echo "<tr>";
    echo "<td>{$rowSection['SECTION_ID']}</td>";
    echo "<td>{$rowSection['COURSE_NUMBER']}</td>";
    echo "<td>{$rowSection['TITLE']}</td>";
    echo "<td>{$rowSection['SEMESTER_SEASON']}</td>";
    echo "<td>{$rowSection['SEMESTER_YEAR']}</td>";
    echo "<td>{$rowSection['CREDIT_HOURS']}</td>";
    echo "<td>{$rowSection['GRADE']}</td>";
    echo "</tr>";
}
echo "</table>";

// Close the cursor and the database connection
oci_free_statement($cursorSections);



oci_close($connection);
?>
<!Doctype html>
<html>
    <head>
        <title></title>
        <body>
        <a href="user_home.php" class="btn">Go back</a>
</body>
</html>