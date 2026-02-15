<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// Verify how we reach here
if (!isset($_POST["update_fail"])) { // from welceomepage.php
  // Get the dnumber, fetch the record to be updated from the database 
  $q_dnumber = $_GET["dnumber"];

  // the sql string
  $sql = "select dnumber, dname, location from dept where dnumber = $q_dnumber";
  //echo($sql);

  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  if ($result == false){
    display_oracle_error_message($cursor);
    die("Query Failed.");
  }

  $values = oci_fetch_array ($cursor);
  oci_free_statement($cursor);

  $dnumber = $values[0];
  $dname = $values[1];
  $location = $values[2];
}
else { // from update_action.php
  // Get the values of the record to be updated directly
  $dnumber = $_POST["dnumber"];
  $dname = $_POST["dname"];
  $location = $_POST["location"];
}

// display the record to be updated.  
echo("
  <form method=\"post\" action=\"dept_update_action.php?sessionid=$sessionid\">
  Number (Read-only): <input type=\"text\" readonly value = \"$dnumber\" size=\"5\" maxlength=\"5\" name=\"dnumber\"> <br /> 
  Name (Required): <input type=\"text\" value = \"$dname\" size=\"50\" maxlength=\"50\" name=\"dname\">  <br />
  Location: <input type=\"text\" value = \"$location\" size=\"100\" maxlength=\"100\" name=\"location\">  <br />
  <input type=\"submit\" value=\"Update\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>

  <form method=\"post\" action=\"department.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");
?>
