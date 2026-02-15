<?php
// setup connection with Oracle
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if ($connection == false){
    // For oci_connect errors, no handle needed
    $e = oci_error();
    die($e['message']);
}

// this is the SQL command to be executed
$query = "SELECT * FROM Sections";

// parse the SQL command
$cursor = oci_parse($connection, $query);
if ($cursor == false){
    // For oci_parse errors, pass the connection handle
    $e = oci_error($connection); 
    die($e['message']);
}

// execute the command
$result = oci_execute($cursor);
if ($result == false){
    // For oci_execute errors pass the cursor handle
    $e = oci_error($cursor); 
    die($e['message']);
}

// display the results
echo "<table border=1>";
echo "<tr> <th>Section ID</th> <th>Course Number</th> <th>Section Time</th> <th>Semester Season</th> <th>Semester Year</th> <th>Date of Enrollment Deadline</th> <th>Section Date</th> <th>Capacity</th> </tr>";

// fetch the result from the cursor one by one
while ($values = oci_fetch_array($cursor)){
    $section_id = $values['SECTION_ID'];
    $course_number = $values['COURSE_NUMBER'];
    $section_time = $values['SECTION_TIME'];
    $semester_season = $values['SEMESTER_SEASON'];
    $semester_year = $values['SEMESTER_YEAR'];
    $enrollment_deadline = $values['DATE_OF_ENROLLMENT_DEADLINE'];
    $section_date = $values['SECTION_DATE'];
    $capacity = $values['CAPACITY'];
    
    echo "<tr><td>$section_id</td><td>$course_number</td><td>$section_time</td><td>$semester_season</td><td>$semester_year</td><td>$enrollment_deadline</td><td>$section_date</td><td>$capacity</td></tr>";
}

echo "</table>";

// free up resources used by the cursor
oci_free_statement($cursor);

// close the connection with oracle
oci_close($connection);
?>

<a href="adminhome.php" class="btn">Go back to home</a>
