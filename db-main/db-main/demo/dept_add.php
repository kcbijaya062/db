<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


// Obtain the inputs from dept_add_action.php
$dnumber = $_POST["dnumber"];
$dname = $_POST["dname"];
$location = $_POST["location"];

// display the insertion form.
echo("
  <form method=\"post\" action=\"dept_add_action.php?sessionid=$sessionid\">
  Number (Up to 5 digits): <input type=\"text\" value = \"$dnumber\" size=\"5\" maxlength=\"5\" name=\"dnumber\"> <br /> 
  Name (Required): <input type=\"text\" value = \"$dname\" size=\"50\" maxlength=\"50\" name=\"dname\">  <br />
  Location: <input type=\"text\" value = \"$location\" size=\"100\" maxlength=\"100\" name=\"location\">  <br />
  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>

  <form method=\"post\" action=\"department.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");

?>
