<?php
session_start();
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $message = 'Failed to connect to database: ' . oci_error()['message'];
}


// Fetch student's current data
function getStudentDetails($studentId) {
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare("SELECT * FROM Students WHERE student_id = ?");
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Update student data
function updateStudent($studentId, $firstName, $lastName, $age, $address, $studentType, $probationStatus) {
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare("UPDATE Students SET first_name = ?, last_name = ?, age = ?, address = ?, student_type = ?, status_on_probation = ? WHERE student_id = ?");
    $stmt->bind_param("ssissss", $firstName, $lastName, $age, $address, $studentType, $probationStatus, $studentId);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assume form fields are named correspondingly
    $result = updateStudent($_POST['student_id'], $_POST['first_name'], $_POST['last_name'], $_POST['age'], $_POST['address'], $_POST['student_type'], $_POST['status_on_probation']);
    $message = $result ? "Update successful." : "Update failed.";
}

if (isset($_GET['student_id'])) {
    $student = getStudentDetails($_GET['student_id']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student</h1>
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>
    <?php if (!empty($student)): ?>
    <form method="POST" action="edit_student.php">
        <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>">
        <input type="text" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>
        <input type="text" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>
        <input type="number" name="age" value="<?= htmlspecialchars($student['age']) ?>" required>
        <input type="text" name="address" value="<?= htmlspecialchars($student['address']) ?>" required>
        <select name="student_type" required>
            <option value="undergraduate" <?= $student['student_type'] == 'undergraduate' ? 'selected' : '' ?>>Undergraduate</option>
            <option value="graduate" <?= $student['student_type'] == 'graduate' ? 'selected' : '' ?>>Graduate</option>
        </select>
        <input type="text" name="status_on_probation" value="<?= htmlspecialchars($student['status_on_probation']) ?>">
        <button type="submit">Update Student</button>
    </form>
    <?php else: ?>
    <p>Student not found.</p>
    <?php endif; ?>
</body>
</html>
