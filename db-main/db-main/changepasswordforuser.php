<?php
// Establish connection with Oracle
error_reporting(E_ALL);
ini_set('display_errors', 1);

$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $message = 'Failed to connect to database: ' . oci_error()['message'];
    // Handle connection error here
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username, old password, and new password from form
    $username = $_POST["username"];
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];

    // Query to check if the username and old password match
    $checkQuery = "SELECT COUNT(*) FROM UserAccountData WHERE username = :username AND password = :oldPassword";
    $checkCursor = oci_parse($connection, $checkQuery);
    oci_bind_by_name($checkCursor, ":username", $username);
    oci_bind_by_name($checkCursor, ":oldPassword", $oldPassword);
    oci_execute($checkCursor);
    $row = oci_fetch_array($checkCursor, OCI_ASSOC);

    // If the username and old password match, update the password
    if ($row['COUNT(*)'] == 1) {
        // Query to update the password
        $updateQuery = "UPDATE UserAccountData SET password = :newPassword WHERE username = :username";

        // Prepare the SQL query for execution
        $cursor = oci_parse($connection, $updateQuery);
        if ($cursor == false) {
            $e = oci_error($connection);
            die($e['message']);
        }

        // Bind parameters for execution
        oci_bind_by_name($cursor, ":newPassword", $newPassword);
        oci_bind_by_name($cursor, ":username", $username);

        // Execute the cursor
        $result = oci_execute($cursor, OCI_NO_AUTO_COMMIT);
        if ($result == false) {
            $e = oci_error($cursor);
            die($e['message']);
        }

        // Commit the transaction
        oci_commit($connection);

        echo "Password updated successfully for user $username. ";
       echo '<a href="login.php" class="btn">Login again</a>';

        //echo "<script>window.location = 'adminlogin.php';</script>"; // Redirect to login page for admin
    } else {
        echo "Error: Username and old password do not match.";
    }

    // Close the connection
    oci_close($connection);

    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
    <h2>Change Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="oldPassword">Old Password:</label><br>
        <input type="password" id="oldPassword" name="oldPassword" required><br><br>

        <label for="newPassword">New Password:</label><br>
        <input type="password" id="newPassword" name="newPassword" required><br><br>
        
        <input type="submit" value="Change Password">
    </form>
</body>
</html>
