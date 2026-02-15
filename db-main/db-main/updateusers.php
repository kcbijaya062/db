<?php
// we Establish connection with Oracle

error_reporting(E_ALL);
ini_set('display_errors', 1);

$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $message = 'Failed to connect to database: ' . oci_error()['message'];
    // we Handle connection error here
}

// we Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and new first name and last name from form
    $username = $_POST["username"];
    $newFirstName = $_POST["newFirstName"];
    $newLastName = $_POST["newLastName"];

    
    // we provide SQL query to update first name and last name based on username
$query = "UPDATE UserAccountData SET firstname = :newFirstName, lastname = :newLastName WHERE username = :username";


    //we  prepare the SQL query for execution
    $cursor = oci_parse($connection, $query);
    if ($cursor == false) {
        $e = oci_error($connection);
        die($e['message']);
    }

    // we bind parameters for execution
    oci_bind_by_name($cursor, ":newFirstName", $newFirstName);
    oci_bind_by_name($cursor, ":newLastName", $newLastName);
    oci_bind_by_name($cursor, ":username", $username);

    //we execute the cursor
    $result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);
    if ($result == false) {
        $e = oci_error($cursor);
        die($e['message']);
    }

    // we commit the transaction
    oci_commit($connection);

    // we close the connection
    oci_close($connection);

    echo "Update successful: First name and last name updated for user $username.";
    exit; // we have to stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Information</title>
</head>
<body>
    <h2>Update User Information</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="newFirstName">New First Name:</label><br>
        <input type="text" id="newFirstName" name="newFirstName" required><br><br>
        
        <label for="newLastName">New Last Name:</label><br>
        <input type="text" id="newLastName" name="newLastName" required><br><br>
        
        <input type="submit" value="Update Information">
    </form>
    <a href="adminhome.php" class="btn">Go back</a>
    <?php
    echo(" <br><br>The user information will be updated successfully as intended");
    ?>
</body>
</html>
