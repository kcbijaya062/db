<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);


// Generate the query section
echo("
  <form method=\"post\" action=\"employee.php?sessionid=$sessionid\">
  Number: <input type=\"text\" size=\"10\" maxlength=\"10\" name=\"q_eid\"> 
  Firstname: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_fname\"> 
  Lastname: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_lname\"> 
  <BR />
  Start Date: 
  Month (mm) <input type=\"text\" size=\"2\" maxlength=\"2\" name=\"q_start_month\"> 
  Day (dd) <input type=\"text\" size=\"2\" maxlength=\"2\" name=\"q_start_day\"> 
  Year (yyyy) <input type=\"text\" size=\"4\" maxlength=\"4\" name=\"q_start_year\"> 
  ");

// create the dropdown list for the departments in the query section.
$sql = "select dnumber, dname from dept order by dnumber";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Query Failed.");
}

echo("
  <BR />
  Department:
  <select name=\"q_dnumber\">
  <option value=\"\">All</option>
  ");

// Fetch the departments from the cursor one by one into the dropdown list.
while ($values = oci_fetch_array ($cursor)){
  $dnumber = $values[0];
  $dname = $values[1];
  echo("
    <option value=\"$dnumber\">$dnumber, $dname</option>
    ");
}
oci_free_statement($cursor);

echo("
  </select>
  <input type=\"submit\" value=\"Search\">
  </form>

  <form method=\"post\" action=\"welcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>

  <form method=\"post\" action=\"emp_add.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Add A New Employee\">
  </form>
  ");

// Interpret the query requirements
$q_eid = $_POST["q_eid"];
$q_fname = $_POST["q_fname"];
$q_lname = $_POST["q_lname"];
$q_start_month = $_POST["q_start_month"];
$q_start_day = $_POST["q_start_day"];
$q_start_year = $_POST["q_start_year"];
$q_dnumber = $_POST["q_dnumber"];

$whereClause = " e.dnumber = d.dnumber ";

if (isset($q_eid) and trim($q_eid) != "") { 
  $whereClause .= " and eid = $q_dnumber"; 
}

if (isset($q_fname) and $q_fname != "") { 
  $whereClause .= " and fname like '%$q_fname%'"; 
}

if (isset($q_lname) and $q_lname != "") { 
  $whereClause .= " and lname like '%$q_lname%'"; 
}

if (isset($q_start_month) and $q_start_month != "") { 
  $whereClause .= " and to_number(to_char(start_date, 'MM')) = $q_start_month"; 
}

if (isset($q_start_day) and $q_start_day != "") { 
  $whereClause .= " and to_number(to_char(start_date, 'DD')) = $q_start_day"; 
}

if (isset($q_start_year) and $q_start_year != "") { 
  $whereClause .= " and to_number(to_char(start_date, 'YYYY')) = $q_start_year"; 
}

if (isset($q_dnumber) and $q_dnumber != "") { 
  $whereClause .= " and e.dnumber like '%$q_dnumber%'"; 
}

// Form the query statement and run it.
$sql = "select eid, fname, lname, to_char(start_date, 'MM/DD/YYYY'), e.dnumber, dname
  from emp e, dept d where $whereClause order by eid";
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
echo "<tr> <th>Id</th> <th>Firstname</th> <th>Lastname</th> <th>Start Date</th> <th>Department No.</th> <th>Department Name</th> <th>Update</th> <th>Delete</th></tr>";

// Fetch the result from the cursor one by one
while ($values = oci_fetch_array ($cursor)){
  $eid = $values[0];
  $fname = $values[1];
  $lname = $values[2];
  $start_date = $values[3];
  $dnumber = $values[4];
  $dname = $values[5];
  echo("<tr>" . 
    "<td>$eid</td> <td>$fname</td> <td>$lname</td> <td>$start_date</td> <td>$dnumber</td> <td>$dname</td>".
    " <td> <A HREF=\"emp_update.php?sessionid=$sessionid&eid=$eid\">Update</A> </td> ".
    " <td> <A HREF=\"emp_delete.php?sessionid=$sessionid&eid=$eid\">Delete</A> </td> ".
    "</tr>");
}
oci_free_statement($cursor);

echo "</table>";

?>
