<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
</head>
<body>
    <h2>Update Student</h2>
    <!-- Search Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="search_student_id">Search by Student ID:</label>
        <input type="text" id="search_student_id" name="search_student_id" required>
        <input type="submit" value="Search">
    </form>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Database connection
    $connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
    if (!$connection) {
        $error_message = oci_error();
        die("Failed to connect to database: " . $error_message['message']);
    } else {
        echo "Connected to Oracle!";
    }

    // Check if search form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_student_id'])) {
        // Retrieve student ID from form data
        $search_student_id = $_POST['search_student_id'];

        // Query to retrieve student information based on student ID
        $query_search_student = "SELECT * FROM Students WHERE student_id = :search_student_id";
        $cursor_search_student = oci_parse($connection, $query_search_student);
        oci_bind_by_name($cursor_search_student, ':search_student_id', $search_student_id);
        oci_execute($cursor_search_student);

        // Fetch student information
        $student_info = oci_fetch_assoc($cursor_search_student);

        if ($student_info) {
            // Display student information
            echo "<h3>Student Information:</h3>";
            echo "First Name: " . $student_info['FIRST_NAME'] . "<br>";
            echo "Last Name: " . $student_info['LAST_NAME'] . "<br>";
            echo "Age: " . $student_info['AGE'] . "<br>";
            echo "Address: " . $student_info['ADDRESS'] . "<br>";
            echo "Student Type: " . $student_info['STUDENT_TYPE'] . "<br>";
            echo "Username: " . $student_info['USERNAME'] . "<br>";
            //echo "date of Admission: " . $student_info['date_of_admission'] . "<br>";
            echo "Student ID: " . $student_info['STUDENT_ID'] . "<br>";
        } else {
            // No student found with the given ID
            echo "No student found with the provided student ID.";
        }

        oci_free_statement($cursor_search_student);
    }
    ?>

</body>
</html>
