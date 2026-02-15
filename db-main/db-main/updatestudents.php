<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo"<H2>Table Students record</H2>";
include "listallstudents.php";
include "searchstudentswhileupdate.php";
echo"<h1>refresh the page to see the result of updated <br>value in the database Students after updating each time</h1>";
// Database connection
Echo"<hr>";
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $error_message = oci_error();
    die("Failed to connect to database: " . $error_message['message']);
} else {
    echo "Connected to Oracle!";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $age = isset($_POST['age']) ? $_POST['age'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $student_type = isset($_POST['student_type']) ? $_POST['student_type'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : '';

    // Updating Students table
    $query_update_student = "UPDATE Students 
                             SET first_name = :first_name, 
                                 last_name = :last_name, 
                                 age = :age, 
                                 address = :address, 
                                 username = :username, 
                                 student_type = :student_type
                             WHERE student_id = :student_id AND username = :username";

    $cursor_update_student = oci_parse($connection, $query_update_student);
    if (!$cursor_update_student) {
        $error_message = oci_error($connection);
        die("Error in OCI parse: " . $error_message['message']);
    }

    // Bind parameters
    oci_bind_by_name($cursor_update_student, ':first_name', $first_name);
    oci_bind_by_name($cursor_update_student, ':last_name', $last_name);
    oci_bind_by_name($cursor_update_student, ':age', $age);
    oci_bind_by_name($cursor_update_student, ':address', $address);
    oci_bind_by_name($cursor_update_student, ':username', $username);
    oci_bind_by_name($cursor_update_student, ':student_type', $student_type);
    oci_bind_by_name($cursor_update_student, ':student_id', $student_id);

    // Execute update query
    $result_update_student = oci_execute($cursor_update_student);
    if (!$result_update_student) {
        $error_message = oci_error($cursor_update_student);
        die("Error updating student: " . $error_message['message']);
    }

    oci_commit($connection);
    oci_free_statement($cursor_update_student);

    echo "Student information updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
</head>
<body>
    <h2>Update Student</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>
        
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>
        
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required><br>
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>
        
        <label for="student_type">Student Type:</label>
        <select id="student_type" name="student_type" required>
            <option value="undergraduate">Undergraduate</option>
            <option value="graduate">Graduate</option>
        </select><br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br>
        <input type="submit" value="Update">
    </form>
    <a href="adminhome.php" class="btn">Go back </a>
</body>
</html>
