<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student Record</title>
</head>
<body>
    <h2>Delete Student Record</h2>
    <h2>Refresh page to see the result after deleting</h2>
    <h6> only enter integer in student Id while deleting</h6>
    <!-- Form to input username and student ID for deletion -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br><br>
        <input type="submit" value="Delete Record">
    </form>

    <?php
    include"listallstudents.php";
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Database connection
    $connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
    if (!$connection) {
        $error_message = oci_error();
        die("Failed to connect to database: " . $error_message['message']);
    }
    //echo"<h4>refresh to see the result<h4>";
     
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['student_id'])) {
        // Retrieve input values
        $username = $_POST['username'];
        $student_id = $_POST['student_id'];

        // Attempt to delete the record from the Enroll table first
        $query_enroll = "DELETE FROM Enroll WHERE student_id = :student_id";
        $stmt_enroll = oci_parse($connection, $query_enroll);
        oci_bind_by_name($stmt_enroll, ':student_id', $student_id);
        $result_enroll = oci_execute($stmt_enroll);

        // Check if deletion from Enroll table was successful
        if ($result_enroll) {
            // Attempt to delete the record from the Students table
            $query_students = "DELETE FROM Students WHERE username = :username AND student_id = :student_id";
            $stmt_students = oci_parse($connection, $query_students);
            oci_bind_by_name($stmt_students, ':username', $username);
            oci_bind_by_name($stmt_students, ':student_id', $student_id);
            $result_students = oci_execute($stmt_students);

            // Check if deletion from Students table was successful
            if ($result_students) {
                echo "<p>Student record deleted successfully.</p>";
            } else {
                // If deletion from Students table failed, display error message
                $error_message = oci_error($stmt_students);
                echo "<p>Error deleting student record: " . $error_message['message'] . "</p>";
            }
        } else {
            // If deletion from Enroll table failed, display error message
            $error_message = oci_error($stmt_enroll);
            echo "<p>Error deleting enrollment records: " . $error_message['message'] . "</p>";
        }

        // Free statement resources
        oci_free_statement($stmt_enroll);
        oci_free_statement($stmt_students);
    }

    // Close database connection
    oci_close($connection);
    ?>
    <a href="adminhome.php" class="btn">Go back </a>
</body>
</html>
