<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Database connection
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $message = 'Failed to connect to database: ' . oci_error()['message'];
    // Handle connection error here
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $student_type = $_POST['student_type'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $student_id = $_POST['student_id']; // Assuming student_id is populated in the form

    // Inserting into Students table
    $query_insert_student = "INSERT INTO Students (student_id, first_name, last_name, age, address, username, date_of_admission, student_type, status_on_probation) 
                             VALUES (:student_id, :first_name, :last_name, :age, :address, :username, SYSDATE, :student_type, NULL)";

    $cursor_insert_student = oci_parse($connection, $query_insert_student);
    if ($cursor_insert_student == false) {
        $e = oci_error($connection);
        die($e['message']);
    }

    oci_bind_by_name($cursor_insert_student, ':student_id', $student_id); // Bind the value from the form

    // Bind other parameters
    oci_bind_by_name($cursor_insert_student, ':first_name', $first_name);
    oci_bind_by_name($cursor_insert_student, ':last_name', $last_name);
    oci_bind_by_name($cursor_insert_student, ':age', $age);
    oci_bind_by_name($cursor_insert_student, ':address', $address);
    oci_bind_by_name($cursor_insert_student, ':username', $username);
    oci_bind_by_name($cursor_insert_student, ':student_type', $student_type);

    $result_insert_student = oci_execute($cursor_insert_student);
    if (!$result_insert_student) {
        $error_message = oci_error($cursor_insert_student)['message'];
        die("Error inserting into Students table: $error_message");
    }

    oci_commit($connection);

    oci_free_statement($cursor_insert_student);

    echo "New student added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student</title>
    <script>
        function generateRandomNumber() {
            // Generate a random number
            var randomNumber = Math.floor(Math.random() * 1000000); // Generate a 6-digit random number
            
            // Populate the input field with the generated random number
            document.getElementById("student_id").value = randomNumber;
        }
    </script>
</head>
<body>
    <h2>Add New Student</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>
        
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>
        
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required><br><br>
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>
        
        <label for="student_type">Student Type:</label>
        <select id="student_type" name="student_type" required>
            <option value="undergraduate">Undergraduate</option>
            <option value="graduate">Graduate</option>
        </select><br><br>
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" readonly>
        <button type="button" onclick="generateRandomNumber()">Generate studentId</button>
        
        <input type="submit" value="Submit">
    </form>
    <a href="adminhome.php" class="btn">Go back </a>
</body>
</html>
