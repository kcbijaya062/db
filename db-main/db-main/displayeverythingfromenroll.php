<?php
// setup connection with Oracle
// $connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
// if ($connection == false){
//     // For oci_connect errors, no handle needed
//     $e = oci_error();
//     die($e['message']);
// }

// this is the SQL command to be executed
$query = "SELECT * FROM Enroll";

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
echo "<tr> <th>Student ID</th> <th>Section ID</th> <th>Grade</th> </tr>";

// fetch the result from the cursor one by one
while ($values = oci_fetch_array($cursor)){
    $student_id = $values[0];
    $section_id = $values[1];
    $grade = $values[2];
    echo "<tr><td>$student_id</td><td>$section_id</td><td>$grade</td></tr>";
}

echo "</table>";

// free up resources used by the cursor
oci_free_statement($cursor);

// close the connection with oracle
oci_close($connection);
?>

<a href="adminlogin.php" class="btn">Go back to login</a>
