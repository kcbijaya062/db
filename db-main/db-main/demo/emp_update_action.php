<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// Suppress PHP auto warning.
ini_set( "display_errors", 0);  

// Obtain information for the record to be updated.
$eid = $_POST["eid"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$start_date = $_POST["start_date"];

$dnumber = trim($_POST["dnumber"]);
//echo($dnumber);
if ($dnumber == "") $dnumber = "NULL";

// Form the sql string and execute it.
$sql = "update emp set fname = '$fname', lname = '$lname', start_date = to_date('$start_date', 'MM/DD/YYYY'), dnumber = $dnumber where eid = $eid";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
  echo "<B>Update Failed.</B> <BR />";

  display_oracle_error_message($cursor);

  die("<i> 

  <form method=\"post\" action=\"emp_update?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"1\" name=\"update_fail\">
  <input type=\"hidden\" value = \"$eid\" name=\"eid\">
  <input type=\"hidden\" value = \"$fname\" name=\"fname\">
  <input type=\"hidden\" value = \"$lname\" name=\"lname\">
  <input type=\"hidden\" value = \"$start_date\" name=\"start_date\">
  <input type=\"hidden\" value = \"$dnumber\" name=\"dnumber\">
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record updated.  Go back.
Header("Location:employee.php?sessionid=$sessionid");
?>
