<?php
// here we check error while establishing connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

//we connect
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $message = 'Failed to connect to database: ' . oci_error()['message'];
    // we handle connection issues
}
 // since we need form submission , we check for the form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    // Retrieve username to delete from form
    $deleteUsername = $_POST["deleteUsername"];

    // Check if the username exists in the database
    $checkQuery = "SELECT COUNT(*) FROM UserAccountData WHERE username = :deleteUsername";
    $checkCursor = oci_parse($connection, $checkQuery);
    oci_bind_by_name($checkCursor, ":deleteUsername", $deleteUsername);
    oci_execute($checkCursor);
    $row = oci_fetch_array($checkCursor, OCI_ASSOC);

    // If the username does not exist, display an error message
    if ($row['COUNT(*)'] == 0) {
        echo "Error: Username '$deleteUsername' does not exist.";
    } else {
        // Prepare SQL query to delete user based on username
        $deleteQuery = "DELETE FROM UserAccountData WHERE username = :deleteUsername";

        // Prepare the SQL query for execution
        $cursor = oci_parse($connection, $deleteQuery);
        if ($cursor == false) {
            $e = oci_error($connection);
            die($e['message']);
        }

        // Bind parameters for execution
        oci_bind_by_name($cursor, ":deleteUsername", $deleteUsername);

        // Execute the cursor
        $result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);
        if ($result == false) {
            $e = oci_error($cursor);
            die($e['message']);
        }

        // Commit the transaction
        oci_commit($connection);

        echo "User with username $deleteUsername deleted successfully.";
    }
}

// Close the connection
oci_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
</head>
<body>
    <h2>Delete User</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="deleteUsername">Username to Delete:</label><br>
        <input type="text" id="deleteUsername" name="deleteUsername" required><br><br>
        <input type="submit" name="delete" value="Delete User">
    </form>
    <a href="adminhome.php" class="btn">Go back</a>
</body>
</html>
