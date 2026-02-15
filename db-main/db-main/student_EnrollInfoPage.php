<html>
<head>
    <title>Student Page for section search</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<?php
$semesterseason = $_POST["searchseason"] ?? '';

if (!isset($semesterseason) or ($semesterseason=="")) {
    $whereClause = " 1=1 ";
} else {
    $semesterseason = strtoupper($semesterseason);
    $whereClause = " SEMESTER_SEASON || SEMESTER_YEAR LIKE '%$semesterseason%' ";
}

$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if ($connection == false) {
    $e = oci_error();
    die($e['message']);
}

echo("<FORM name=\"searchseason\" method=\"POST\" action=\"student_EnrollInfoPage.php\"> " .
 "semester year : <INPUT type=\"text\" name=\"searchseason\"> " .
 "<INPUT type=\"submit\" name=\"btnSubmit\" value=\"search based on semester year\"> " .
 "</FORM>");

$query = "SELECT s.section_id, s.course_number, c.title, c.credit_hours, s.semester_season, s.semester_year, s.section_time, s.date_of_enrollment_deadline, s.capacity, (s.capacity - COUNT(e.student_id)) AS available_seats 
          FROM Sections s 
          INNER JOIN Course c ON s.course_number = c.course_number 
          LEFT JOIN Enroll e ON s.section_id = e.section_id 
          WHERE " . $whereClause . "
          GROUP BY s.section_id, s.course_number, c.title, c.credit_hours, s.semester_season, s.semester_year, s.section_time, s.date_of_enrollment_deadline, s.capacity";

$cursor = oci_parse($connection, $query);

if ($cursor == false) {
    $e = oci_error($connection);
    die($e['message']);
}

$result = oci_execute($cursor);
if ($result == false) {
    $e = oci_error($cursor);
    die($e['message']);
}

echo "<h2>Search Results</h2>";
echo "<form action=\"seasonproc.php\" method=\"post\">";
echo "<table>"; // add table opening tag
echo "<tr><th>Section ID</th><th>Course Number</th><th>Course Title</th><th>Credit Hours</th>
      <th>Semester</th><th>Section Date/Time</th><th>Enrollment Deadline</th><th>Capacity</th>
      <th>Available Seats</th></tr>";

while ($row = oci_fetch_assoc($cursor)) {
    echo "<tr>";
    echo "<td>{$row['SECTION_ID']}</td>";
    echo "<td>{$row['COURSE_NUMBER']}</td>";
    echo "<td>{$row['TITLE']}</td>";
    echo "<td>{$row['CREDIT_HOURS']}</td>";
    echo "<td>{$row['SEMESTER_SEASON']} {$row['SEMESTER_YEAR']}</td>";
    echo "<td>{$row['SECTION_TIME']}</td>";
    echo "<td>{$row['DATE_OF_ENROLLMENT_DEADLINE']}</td>";
    echo "<td>{$row['CAPACITY']}</td>";
    echo "<td>{$row['AVAILABLE_SEATS']}</td>";
    echo "</tr>";
}

echo "</table>";
echo "<INPUT type=\"submit\" name=\"btnSubmit\" value=\"filter\">";
echo "</form>";
oci_free_statement($cursor);
oci_close($connection);
?>

<a href="creditsearchpage.php">wanna search based on course_number?? Click here</a><br>
<a href="user_home.php" class="btn">Go back</a>
</body>
</html>
