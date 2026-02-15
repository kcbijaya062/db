<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


// Obtain input from department.php
$q_dnumber = $_GET["dnumber"];

// Retrieve the tuple to be deleted and display it.
$sql = "select dnumber, dname, location from dept where dnumber = $q_dnumber";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){ // error unlikely
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

if (!($values = oci_fetch_array ($cursor))) {
  // Record already deleted by a separate session.  Go back.
  Header("Location:department.php?sessionid=$sessionid");
}
oci_free_statement($cursor);

$dnumber = $values[0];
$dname = $values[1];
$location = $values[2];

// Display the tuple to be deleted
echo("
  <form method=\"post\" action=\"dept_delete_action.php?sessionid=$sessionid\">
  Number (Read-only): <input type=\"text\" readonly value = \"$dnumber\" name=\"dnumber\"> <br /> 
  Name: <input type=\"text\" disabled value = \"$dname\" name=\"dname\">  <br />
  Location: <input type=\"text\" disabled value = \"$location\" name=\"location\">  <br />
  <input type=\"submit\" value=\"Delete\">
  </form>

  <form method=\"post\" action=\"department.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");

?>
