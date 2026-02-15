<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


$q_eid = $_GET["eid"];


// Fetech the record to be deleted and display it
$sql = "select eid, fname, lname, to_char(start_date, 'MM/DD/YYYY'), dnumber from emp where eid = $q_eid";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

if (!($values = oci_fetch_array ($cursor))) {
  // Record already deleted by a separate session.  Go back.
  Header("Location:employee.php?sessionid=$sessionid");
}
oci_free_statement($cursor);

$eid = $values[0];
$fname = $values[1];
$lname = $values[2];
$start_date = $values[3];
$dnumber = $values[4];

// Display the record to be deleted.
echo("
  <form method=\"post\" action=\"emp_delete_action.php?sessionid=$sessionid\">
  Id (Read-only): <input type=\"text\" readonly value = \"$eid\" size=\"10\" maxlength=\"10\" name=\"eid\"> <br /> 
  Firstname: <input type=\"text\" disabled value = \"$fname\" size=\"20\" maxlength=\"30\" name=\"fname\">  <br />
  Lastname: <input type=\"text\" disabled value = \"$lname\" size=\"20\" maxlength=\"30\" name=\"lname\">  <br />
  Start Date: <input type=\"text\" disabled value = \"$start_date\" size=\"10\" maxlength=\"10\" name=\"start_date\">  <br />
  ");

// Display department list
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
  Department:
  <select disabled name=\"dnumber\">
  <option value=\"\">Choose One:</option>
  ");

// Fetch the departments from the cursor one by one into the dropdown list
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
  </select>  <input type=\"submit\" value=\"Delete\">
  </form>

  <form method=\"post\" action=\"employee.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");

?>
