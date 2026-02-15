<?php
// we start the session if not already started
session_start();
include "StudentInfo.php";
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // we Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve the username of the logged-in student
$username = $_SESSION['username'];

// Here we check errors while establishing connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Attempt to connect to the database
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");

// Check if the connection was successful
if (!$connection) {
    // Display error message and stop further execution
    $errorMessage = oci_error();
    echo "Failed to connect to database: " . $errorMessage['message'];
    exit();
}
// checking the form with username value is submitted or not;
if ($_SERVER["REQUEST_METHOD"] =="POST"){
    $username = $_POST['username'];
}
// we use this SQL command to be executed
$query = "select * from Students where username = :username";


//we parse the SQL command
$cursor = oci_parse ($connection, $query);
if ($cursor == false){
 // For oci_parse errors, pass the connection handle
 $e = oci_error($connection); 
 die($e['message']);
}

// we bind parameter here;
oci_bind_by_name($cursor, ':username', $username);

// we execute the command
$result = oci_execute ($cursor);
if ($result == false){
 // For oci_execute errors pass the cursor handle
 $e = oci_error($cursor); 
 die($e['message']);
}
echo "<h2>Student details are :</h2>";
while ($row = oci_fetch_assoc($cursor)) {
    echo "<p>ID: {$row['STUDENT_ID']}</p>";
    echo "<p>FIRST NAME: {$row['FIRST_NAME']}</p>";
    echo "<p>LAST NAME: {$row['LAST_NAME']}</p>";
    echo "<p>AGE: {$row['AGE']}</p>";
    echo "<p>ADDRESS: {$row['ADDRESS']}</p>";
    echo "<p>USERNAME : {$row['USERNAME']}</p>";
    echo "<p> DATE_OF_ADMISSION: {$row['DATE_OF_ADMISSION']}</p>";
    echo "<p>STUDENT_TYPE: {$row['STUDENT_TYPE']}</p>";
    //echo "<p>STATUS OF PROBOCATION: {$row['STATUS ON PROBATION']}</p>";
    echo "<p>STATUS OF PROBATION: {$row['STATUS_ON_PROBATION']}</p>";

    echo "<hr>";
    }
    
oci_commit($connection);
oci_close($connection);
echo " this gives information that matches with the given username from Students table with all attributes that has been logged in now";
echo" but here you have to still give your own username to access your information from Student table";
echo" when you give other user's username in the field it will display nothing";
?>
<!Doctype html>
<html>
    <head>
        <title></title>
        <body>
        <a href="user_home.php" class="btn">Go back</a>
</body>
</html>

