<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


ini_set( "display_errors", 0);  


$eid = $_POST["eid"];

// Form the sql string and execute it.
$sql = "delete from emp where eid = $eid";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  // Error handling interface.
  echo "<B>Deletion Failed.</B> <BR />";

  display_oracle_error_message($cursor);

  die("<i> 

  <form method=\"post\" action=\"employee.php?sessionid=$sessionid\">
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record deleted.  Go back.
Header("Location:employee.php?sessionid=$sessionid");
?>
