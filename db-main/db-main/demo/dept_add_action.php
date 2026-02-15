<?
ini_set( "display_errors", 0);  

include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$dnumber = trim($_POST["dnumber"]);
if ($dnumber == "") $dnumber = 'NULL';

$dname = $_POST["dname"];
$location = $_POST["location"];

// the sql string
$sql = "insert into dept values ($dnumber, '$dname', '$location')";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  echo "<B>Insertion Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  <form method=\"post\" action=\"dept_add?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"$dnumber\" name=\"dnumber\">
  <input type=\"hidden\" value = \"$dname\" name=\"dname\">
  <input type=\"hidden\" value = \"$location\" name=\"location\">
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

Header("Location:department.php?sessionid=$sessionid");
?>
