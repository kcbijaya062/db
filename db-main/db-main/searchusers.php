<?php
// fetch queryUsername argument if any
$queryUsername = strtolower($_POST["queryUsername"]) ?? ""; // Convert to lowercase and use null coalescing operator

// Output the value of $queryUsername for debugging
var_dump($queryUsername);

// $queryUsername is the variable that holds the query condition
if ($queryUsername === "")
    $whereClause = " 1=1 ";
else {
    $whereClause = " LOWER(username) like '%$queryUsername%' "; // Use LOWER() function to ensure case-insensitive search
}
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if ($connection == false) {
    $e = oci_error();
    die($e['message']);
}
// processing queries based on username
echo("<form name=\"searchusers\" method=\"POST\" action=\"searchusers.php\"> " .
    "Search: <input type=\"text\" name=\"queryUsername\" value=\"$queryUsername\"> " . // Keep lowercase value in the input field
    "<input type=\"submit\" name=\"btnSubmit\" value=\"Search\"> " .
    "</form>");
// the query string
$query = "select username, firstname, lastname from UserAccountData where " . $whereClause;
$cursor = oci_parse($connection, $query);
if ($cursor == false) {
    $e = oci_error($connection);
    die($e['message']);
}
$result = oci_execute($cursor);
if ($result == false) {
    $e = oci_error($cursor);
    die($e['message']);
}
// the form to process the selected courses
echo "<form action=\"searchselectresult.php\" method=\"post\">";
echo "<table border=1>";
echo "<tr><td><b>Username</b></td>" .
    "<td><b>Firstname</b></td>" . "<td><b>Lastname</b></td>" .
    "<td></td></tr>";
// fetch the result from the cursor one by one
while ($values = oci_fetch_array($cursor)) {
    $username = $values[0];
    $firstname = $values[1];
    $lastname = $values[2];
    echo "<tr><td>$username</td>" .
        "<td>$firstname</td>" . "<td>$lastname</td>" .
        "<td><input type=\"checkbox\" name=\"usernameList[]\" value=\"$username\"></td>" .
        "</tr>";
}
echo "</table>";
echo "<input type=\"submit\" name=\"btnSubmit\" value=\"chooseUsers\">";
echo "</form>";
oci_free_statement($cursor);
oci_close($connection);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Page</title>
</head>
<body>

<a href="adminhome.php" class="btn">Go back</a>

</body>
</html>
