<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


// Generate the query section
echo("
  <form method=\"post\" action=\"department.php?sessionid=$sessionid\">
  Number: <input type=\"text\" size=\"5\" maxlength=\"5\" name=\"q_dnumber\"> 
  Name: <input type=\"text\" size=\"20\" maxlength=\"50\" name=\"q_dname\"> 
  Location: <input type=\"text\" size=\"20\" maxlength=\"100\" name=\"q_location\"> 
  <input type=\"submit\" value=\"Search\">
  </form>

  <form method=\"post\" action=\"welcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>

  <form method=\"post\" action=\"dept_add.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Add A New Department\">
  </form>
  ");


// Interpret the query requirements
$q_dnumber = $_POST["q_dnumber"];
$q_dname = $_POST["q_dname"];
$q_location = $_POST["q_location"];

$whereClause = " 1=1 ";

if (isset($q_dnumber) and trim($q_dnumber)!= "") { 
  $whereClause .= " and dnumber = $q_dnumber"; 
}

if (isset($q_dname) and $q_dname!= "") { 
  $whereClause .= " and dname like '%$q_dname%'"; 
}

if (isset($q_location) and $q_location!= "") { 
  $whereClause .= " and location like '%$q_location%'"; 
}


// Form the query and execute it
$sql = "select dnumber, dname, location from dept where $whereClause order by dnumber";
//echo($sql);

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}


// Display the query results
echo "<table border=1>";
echo "<tr> <th>Number</th> <th>Name</th> <th>Location</th> <th>Update</th> <th>Delete</th></tr>";

// Fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
  $dnumber = $values[0];
  $dname = $values[1];
  $location = $values[2];
  echo("<tr>" . 
    "<td>$dnumber</td> <td>$dname</td> <td>$location</td> ".
    " <td> <A HREF=\"dept_update.php?sessionid=$sessionid&dnumber=$dnumber\">Update</A> </td> ".
    " <td> <A HREF=\"dept_delete.php?sessionid=$sessionid&dnumber=$dnumber\">Delete</A> </td> ".
    "</tr>");
}
oci_free_statement($cursor);

echo "</table>";
?>
