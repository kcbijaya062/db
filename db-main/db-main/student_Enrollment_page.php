<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $errorMessage = oci_error();
    echo "Failed to connect to database: " . $errorMessage['message'];
    exit();
}

// Retrieve form data
$semester = $_POST['semester'] ?? '';
$courseNumber = $_POST['course_number'] ?? '';

// Construct the query to search for sections
$query = "SELECT sec.section_id, sec.course_number, c.title, c.credit_hours, sec.semester_season, 
                 sec.semester_year, sec.section_date, sec.section_time, sec.date_of_enrollment_deadline,
                 sec.capacity, (sec.capacity - COUNT(e.section_id)) AS available_seats
          FROM Sections sec
          INNER JOIN Course c ON sec.course_number = c.course_number
          LEFT JOIN Enroll e ON sec.section_id = e.section_id
          WHERE (sec.semester_season || ' ' || sec.semester_year) LIKE :semester 
          AND sec.course_number LIKE :courseNumber
          GROUP BY sec.section_id, sec.course_number, c.title, c.credit_hours, sec.semester_season, 
                   sec.semester_year, sec.section_date, sec.section_time, sec.date_of_enrollment_deadline, 
                   sec.capacity";

// Prepare the query
$statement = oci_parse($connection, $query);
if (!$statement) {
    $errorMessage = oci_error($connection);
    echo "Failed to prepare query: " . $errorMessage['message'];
    exit();
}

// Bind parameters and execute the query
oci_bind_by_name($statement, ":semester", $semester);
oci_bind_by_name($statement, ":courseNumber", $courseNumber);
$result = oci_execute($statement);

// Display search results
echo "<h2>Search Results</h2>";
echo "<table border='1'>";
echo "<tr><th>Section ID</th><th>Course Number</th><th>Course Title</th><th>Credit Hours</th>
      <th>Semester</th><th>Section Date/Time</th><th>Enrollment Deadline</th><th>Capacity</th>
      <th>Available Seats</th></tr>";

while ($row = oci_fetch_assoc($statement)) {
    echo "<tr>";
    echo "<td>{$row['SECTION_ID']}</td>";
    echo "<td>{$row['COURSE_NUMBER']}</td>";
    echo "<td>{$row['TITLE']}</td>";
    echo "<td>{$row['CREDIT_HOURS']}</td>";
    echo "<td>{$row['SEMESTER_SEASON']} {$row['SEMESTER_YEAR']}</td>";
    echo "<td>{$row['SECTION_DATE']} {$row['SECTION_TIME']}</td>";
    echo "<td>{$row['DATE_OF_ENROLLMENT_DEADLINE']}</td>";
    echo "<td>{$row['CAPACITY']}</td>";
    echo "<td>{$row['AVAILABLE_SEATS']}</td>";
    echo "</tr>";
}

echo "</table>";

// Close the database connection
oci_free_statement($statement);
oci_close($connection);
?>
<form action="enroll.php" method="post">
    <label for="semester">Search by Semester:</label>
    <input type="text" id="semester" name="semester" placeholder="Enter semester (e.g., Spring 2024)"><br>
    <label for="course_number">Search by Course Number:</label>
    <input type="text" id="course_number" name="course_number" placeholder="Enter partial course number (e.g., C01)"><br>
    <input type="submit" value="Search">
</form>
