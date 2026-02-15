<?
// setup connection with Oracle
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if ($connection == false){
 // For oci_connect errors, no handle needed
$e = oci_error();
die($e['message']);
}
// this is the SQL command to be executed
$query = "select * from UserAccountData ";
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
echo "<tr> <th>UserName</th> <th>Password</th> <th>Firstname</th> <th>lastname</th>" . 
 " </tr>";
// fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
 $username = $values[0];
 $password = $values[1];
$firstname = $values[2];
 $lastname = $values[3];
 echo "<tr><td>$username</td> <td>$password</td><td>$firstname</td> <td>$lastname</td>" .
 "<td>$dept</td> </tr>";
}
echo "</table>";
// free up resources used by the cursor
oci_free_statement($cursor);
// close the connection with oracle
oci_close ($connection);
?>
<!Doctype Html>
<html><head><body>
<a href="adminhome.php" class="btn">Go back</a>
</body>
    </html>