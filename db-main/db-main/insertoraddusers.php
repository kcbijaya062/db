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
    $password = $_POST["password"];
    $FirstName = $_POST["FirstName"];
    $LastName = $_POST["LastName"];

    
    $query = "INSERT INTO UserAccountData (username, password, firstname, lastname) VALUES (:username, :password, :FirstName, :LastName)";


    //we  prepare the SQL query for execution
    $cursor = oci_parse($connection, $query);
    if ($cursor == false) {
        $e = oci_error($connection);
        die($e['message']);
    }

    // we bind parameters for execution
    oci_bind_by_name($cursor, ":FirstName", $FirstName);
    oci_bind_by_name($cursor, ":LastName", $LastName);
    oci_bind_by_name($cursor, ":username", $username);
    oci_bind_by_name($cursor, ":password", $password);
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

    echo "User with  $username added successful:";
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
        
        <label for="password">password</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="FirstName"> First Name:</label><br>
        <input type="text" id="FirstName" name="FirstName" required><br><br>
        
        <label for="LastName"> Last Name:</label><br>
        <input type="text" id="LastName" name="LastName" required><br><br>
        
        <input type="submit" value="User Information Added">
    </form>
    <a href="adminhome.php" class="btn">Go back</a>
    <?php
    echo(" <br><br>The user information will be added successfully as intended");
    ?>
</body>
</html>
