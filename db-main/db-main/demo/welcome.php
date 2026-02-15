<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


// Here we can generate the content of the welcome page
echo("Data Management Menu: <br />");
echo("<UL>
  <LI><A HREF=\"department.php?sessionid=$sessionid\">Department</A></LI>
  <LI><A HREF=\"employee.php?sessionid=$sessionid\">Employee</A></LI>
  </UL>");

echo("<br />");
echo("<br />");
echo("Click <A HREF = \"logout_action.php?sessionid=$sessionid\">here</A> to Logout.");
?>
