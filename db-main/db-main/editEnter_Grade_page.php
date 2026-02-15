<?php
//session_start();
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $message = 'Failed to connect to database: ' . oci_error()['message'];
}


function updateGrade($studentId, $sectionId, $grade) {
    $conn = getDatabaseConnection();
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO Enroll (student_id, section_id, grade) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE grade = ?");
        $stmt->bind_param("ssss", $studentId, $sectionId, $grade, $grade);
        $stmt->execute();

        // Recalculate GPA and update probation status
        $stmt = $conn->prepare("SELECT AVG(grade) AS gpa FROM Enroll WHERE student_id = ?");
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $gpa = $result['gpa'];
        $newProbationStatus = $gpa < 2.0 ? 'Y' : 'N';

        $stmt = $conn->prepare("UPDATE Students SET status_on_probation = ? WHERE student_id = ?");
        $stmt->bind_param("ss", $newProbationStatus, $studentId);
        $stmt->execute();

        $conn->commit();
        echo "Grade entered and probation status updated.";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to update grade: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    updateGrade($_POST['student_id'], $_POST['section_id'], $_POST['grade']);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Enter Grades</title>
</head>
<body>
    <h1>Enter Student Grades</h1>
    <form method="POST" action="editEnter_Grade.php">
        <input type="text" name="student_id" placeholder="Student ID" required>
        <input type="text" name="section_id" placeholder="Section ID" required>
        <input type="text" name="grade" placeholder="Grade" required>
        <button type="submit">Enter Grade</button>
    </form>
</body>
</html>
