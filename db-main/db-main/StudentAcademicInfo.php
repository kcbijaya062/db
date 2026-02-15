<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Attempt to connect to the database
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
// Check if the connection was successful
if (!$connection) {
    // we display error message and stop further execution
    $errorMessage = oci_error();
    echo "Failed to connect to database: " . $errorMessage['message'];
    exit();
}
echo"here we have displayed every details we have in course table as a reference";
include"course_view.php";
echo"we can have these courses to be choosen";
include "view_courses.php";

oci_close($connection);
?>
<div class="container">
    <h1>Student Academic Information</h1>
    <div class="info">
        <h1> Academic Information and Section details will be displayed here<h1>
</div>
