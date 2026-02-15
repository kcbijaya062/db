<?
// setup connection with Oracle
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if ($connection == false){
 // For oci_connect errors, no handle needed
$e = oci_error();
die($e['message']);
}
// this is the SQL command to be executed
$query = "select * from Students ";
// parse the SQL command
$cursor = oci_parse ($connection, $query);
if ($cursor == false){
 // For oci_parse errors, pass the connection handle
 $e = oci_error($connection); 
 die($e['message']);
}
// execute the command
$result = oci_execute ($cursor);
if ($result == false){
 // For oci_execute errors pass the cursor handle
 $e = oci_error($cursor); 
 die($e['message']);
}
// display the results
echo "<table border=1>";
echo "<tr> <th>student_id</th> <th>First Name</th> <th>Last Name</th> <th>Age</th><th>Address</th> <th>Username</th><th>date_of_admission</th> <th>student_type</th><th>status_on_probation</th>" . 
 " </tr>";
// fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
 $student_id = $values[0];
 $firstname = $values[1];
$lastname = $values[2];
 $age = $values[3];
 $address = $values[4];
 $username = $values[5];
 $dateofadmission = $values[6];
 $studenttype = $values[7];
 $studentonprobation = $values[8];
 echo "<tr><td>$student_id</td><td>$firstname</td><td>$lastname</td> <td>$age</td><td>$address</td><td>$username</td><td>$dateofadmission</td>" .
 "<td>$studenttype</td><td>$studentonprobation</td> </tr>";
}
echo "</table>";
// free up resources used by the cursor
oci_free_statement($cursor);
// close the connection with oracle
oci_close ($connection);
?>
<a href="adminlogin.php" class="btn">Go back to login </a>