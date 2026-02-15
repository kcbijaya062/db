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
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $student_id = $_POST["student_id"];
        $section_id = $_POST["section_id"];
        $new_grade = $_POST["grade"];

        // Get the old grade to calculate GPA
        $old_grade_query = "SELECT grade FROM Enroll WHERE student_id = :student_id AND section_id = :section_id";
        $old_grade_stmt = oci_parse($connection, $old_grade_query);
        oci_bind_by_name($old_grade_stmt, ":student_id", $student_id);
        oci_bind_by_name($old_grade_stmt, ":section_id", $section_id);
        oci_execute($old_grade_stmt);
        $old_grade_row = oci_fetch_assoc($old_grade_stmt);
        $old_grade = $old_grade_row['GRADE'];

        // Create or replace the stored procedure
        $procedure_query = "CREATE OR REPLACE PROCEDURE UpdateStudentGrade(
            p_student_id IN VARCHAR2,
            p_section_id IN INT,
            p_grade IN VARCHAR2
        ) AS
        BEGIN
            UPDATE Enroll
            SET grade = p_grade
            WHERE student_id = p_student_id
            AND section_id = p_section_id;
            COMMIT;
        END;";
        $procedure_stmt = oci_parse($connection, $procedure_query);
        oci_execute($procedure_stmt);

        // Execute the stored procedure
        $update_query = "BEGIN UpdateStudentGrade(:student_id, :section_id, :grade); END;";
        $update_stmt = oci_parse($connection, $update_query);
        oci_bind_by_name($update_stmt, ":student_id", $student_id);
        oci_bind_by_name($update_stmt, ":section_id", $section_id);
        oci_bind_by_name($update_stmt, ":grade", $new_grade);
        oci_execute($update_stmt);

        // Calculate GPA
        $gpa_query = "SELECT AVG(CASE WHEN grade = 'A' THEN 4 
                                         WHEN grade = 'B' THEN 3 
                                         WHEN grade = 'C' THEN 2 
                                         WHEN grade = 'D' THEN 1 
                                         ELSE 0 END) AS GPA
                      FROM Enroll
                      WHERE student_id = :student_id";
        $gpa_stmt = oci_parse($connection, $gpa_query);
        oci_bind_by_name($gpa_stmt, ":student_id", $student_id);
        oci_execute($gpa_stmt);
        $gpa_row = oci_fetch_assoc($gpa_stmt);
        $gpa = $gpa_row['GPA'];

        // Check GPA and update probation status
        $probation = ($gpa < 2.0) ? 'Y' : 'N';
        $update_probation_query = "UPDATE Students SET status_on_probation = :probation WHERE student_id = :student_id";
        $update_probation_stmt = oci_parse($connection, $update_probation_query);
        oci_bind_by_name($update_probation_stmt, ":probation", $probation);
        oci_bind_by_name($update_probation_stmt, ":student_id", $student_id);
        oci_execute($update_probation_stmt);

        echo "<p>Grade updated successfully!</p>";

        // Close the connection
        oci_close($connection);
    
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="student_id">Student ID:</label><br>
        <input type="text" id="student_id" name="student_id" required><br><br>
        
        <label for="section_id">Section ID:</label><br>
        <input type="text" id="section_id" name="section_id" required><br><br>

        <label for="grade">New Grade:</label><br>
        <input type="text" id="grade" name="grade" required><br><br>
        
        <input type="submit" value="Update Grade">
    </form>
    <a href="gradeediting.php" class="btn">Wanna edit previous grade ?? ,visit this link</a>
    <a href="adminhome.php" class="btn">Go back</a>
</body>
</html>
