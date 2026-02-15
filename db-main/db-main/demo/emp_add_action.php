<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// Suppress PHP auto warnings.
ini_set( "display_errors", 0);  

// Get the values of the record to be inserted.
$eid = trim($_POST["eid"]);
if ($eid == "") $eid = "NULL";

$fname = $_POST["fname"];
$lname = $_POST["lname"];
$start_date = $_POST["start_date"];

$dnumber = trim($_POST["dnumber"]);
//echo($dnumber);
if ($dnumber == "") $dnumber = "NULL";

// Form the insertion sql string and run it.
$sql = "insert into emp values ($eid, '$fname', '$lname', to_date('$start_date', 'MM/DD/YYYY'), $dnumber)";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
  echo "<B>Insertion Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  <form method=\"post\" action=\"emp_add?sessionid=$sessionid\">

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

// Record inserted.  Go back.
Header("Location:employee.php?sessionid=$sessionid");
?>
