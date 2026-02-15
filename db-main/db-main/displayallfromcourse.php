<?php
// setup connection with Oracle
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if ($connection == false){
    // For oci_connect errors, no handle needed
    $e = oci_error();
    die($e['message']);
}

// this is the SQL command to be executed
$query = "SELECT * FROM Course";

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
echo "<tr> <th>Course Number</th> <th>Title</th> <th>Credit Hours</th> </tr>";

// fetch the result from the cursor one by one
while ($values = oci_fetch_array($cursor)){
    $course_number = $values['COURSE_NUMBER'];
    $title = $values['TITLE'];
    $credit_hours = $values['CREDIT_HOURS'];
    
    echo "<tr><td>$course_number</td><td>$title</td><td>$credit_hours</td></tr>";
}

echo "</table>";

// free up resources used by the cursor
oci_free_statement($cursor);

// close the connection with oracle
oci_close($connection);
?>

<a href="adminhome.php" class="btn">Go back to Home</a>
