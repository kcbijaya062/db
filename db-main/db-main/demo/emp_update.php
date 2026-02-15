<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// Verify where we are from, employee.php or  emp_update_action.php.
if (!isset($_POST["update_fail"])) { // from employee.php
  // Fetch the record to be updated.
  $q_eid = $_GET["eid"];

  // the sql string
  $sql = "select eid, fname, lname, to_char(start_date, 'MM/DD/YYYY'), dnumber from emp where eid = $q_eid";
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

  $eid = $values[0];
  $fname = $values[1];
  $lname = $values[2];
  $start_date = $values[3];
  $dnumber = $values[4];
}
else { // from emp_update_action.php
  // Obtain values of the record to be updated directly.
  $eid = $_POST["eid"];
  $fname = $_POST["fname"];
  $lname = $_POST["lname"];
  $start_date = $_POST["start_date"];
  $dnumber = $_POST["dnumber"];
}

// Display the record to be updated.
echo("
  <form method=\"post\" action=\"emp_update_action.php?sessionid=$sessionid\">
  Id (Read-only): <input type=\"text\" readonly value = \"$eid\" size=\"10\" maxlength=\"10\" name=\"eid\"> <br /> 
  Firstname (Required): <input type=\"text\" value = \"$fname\" size=\"20\" maxlength=\"30\" name=\"fname\">  <br />
  Lastname (Required): <input type=\"text\" value = \"$lname\" size=\"20\" maxlength=\"30\" name=\"lname\">  <br />
  Start Date (mm/dd/yyyy): <input type=\"text\" value = \"$start_date\" size=\"10\" maxlength=\"10\" name=\"start_date\">  <br />
  ");

// Display department list as part of interface to display the record to be updated.
// create the dropdown list for the departments.
$sql = "select dnumber, dname from dept order by dnumber";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Query Failed.");
}

echo("
  Department (Required):
  <select name=\"dnumber\">
  <option value=\"\">Choose One:</option>
  ");

// Fetch the departments from the cursor one by one into the dropdown list.
while ($values = oci_fetch_array ($cursor)){
  $d_dnumber = $values[0];
  $d_dname = $values[1];
  if (!isset($dnumber) or $dnumber == "" or $d_dnumber != $dnumber) {
    echo("
      <option value=\"$d_dnumber\">$d_dnumber, $d_dname</option>
      ");
  }
  else {
    echo("
      <option selected value=\"$d_dnumber\">$d_dnumber, $d_dname</option>
      ");
  }
}
oci_free_statement($cursor);

echo("
  </select>  <input type=\"submit\" value=\"Update\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>

  <form method=\"post\" action=\"employee.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");
?>
