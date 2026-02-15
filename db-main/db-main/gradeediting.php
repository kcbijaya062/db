<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Grade</title>
</head>
<body>
    <h1>Update Student Grade</h1>
    <?php
    // Establish connection with Oracle
    $connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
    if (!$connection) {
        $message = 'Failed to connect to database: ' . oci_error()['message'];
    
    }
    // Create or replace the trigger
    $trigger_query = "CREATE OR REPLACE TRIGGER Enroll_Update_Grade
                      BEFORE UPDATE ON Enroll
                      FOR EACH ROW
                      BEGIN
                          IF :NEW.grade IS NULL THEN
                              :NEW.grade := 'N/A'; -- Set a default grade if not provided
                          END IF;
                      END;";
    $trigger_stmt = oci_parse($connection, $trigger_query);
    oci_execute($trigger_stmt);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $student_id = $_POST["student_id"];
        $section_id = $_POST["section_id"];
        $new_grade = $_POST["grade"];

        // Execute the stored procedure
        $update_query = "BEGIN UpdateStudentGrade(:student_id, :section_id, :grade); END;";
        $update_stmt = oci_parse($connection, $update_query);
        oci_bind_by_name($update_stmt, ":student_id", $student_id);
        oci_bind_by_name($update_stmt, ":section_id", $section_id);
        oci_bind_by_name($update_stmt, ":grade", $new_grade);
        oci_execute($update_stmt);

        echo "<p>Grade updated successfully!</p>";
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="student_id">Student ID:</label><br>
        <input type="text" id="student_id" name="student_id" required><br><br>
        
        <label for="section_id">Section ID:</label><br>
        <input type="text" id="section_id" name="section_id" required><br><br>

        <label for="grade">New Grade:</label><br>
        <input type="text" id="grade" name="grade"><br><br>
        
        <input type="submit" value="Update Grade">
    </form>
</body>
</html>
