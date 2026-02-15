<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

// Get values for the record to be added if from emp_add_action.php
$eid = $_POST["eid"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$start_date = $_POST["start_date"];
$dnumber = $_POST["dnumber"];

// display the insertion form.
echo("
  <form method=\"post\" action=\"emp_add_action.php?sessionid=$sessionid\">
  Id (Required, up to 10 digits): <input type=\"text\" value = \"$eid\" size=\"10\" maxlength=\"10\" name=\"eid\"> <br /> 
  Firstname (Required): <input type=\"text\" value = \"$fname\" size=\"20\" maxlength=\"30\" name=\"fname\">  <br />
  Lastname (Required): <input type=\"text\" value = \"$lname\" size=\"20\" maxlength=\"30\" name=\"lname\">  <br />
  Start Date (mm/dd/yyyy): <input type=\"text\" value = \"$start_date\" size=\"10\" maxlength=\"10\" name=\"start_date\">  <br />
  ");

// display the department list in the insertion form.
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

// fetch the departments from the cursor one by one and put into the dropdown menu
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
  </select>
  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>

  <form method=\"post\" action=\"employee.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");
?>
