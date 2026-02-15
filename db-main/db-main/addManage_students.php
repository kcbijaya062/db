<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: user_home.php");
    exit;
}

$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $message = 'Failed to connect to database: ' . oci_error()['message'];
}


// Function to fetch and filter students
function getStudents($search = '') {
    global $connection;
    $searchTerm = '%' . $search . '%';
    $sql = "SELECT student_id, first_name, last_name, student_type, status_on_probation FROM Students WHERE student_id || first_name || last_name || student_type LIKE :searchTerm";
    $stmt = oci_parse($connection, $sql);
    oci_bind_by_name($stmt, ":searchTerm", $searchTerm);
    oci_execute($stmt);
    $students = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $students[] = $row;
    }
    return $students;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $studentId = $_POST['student_id'];
    $sql = "DELETE FROM Students WHERE student_id = :studentId";
    $stmt = oci_parse($connection, $sql);
    oci_bind_by_name($stmt, ":studentId", $studentId);
    oci_execute($stmt);
    echo "Student deleted successfully.";
}


$students = getStudents($_GET['search'] ?? '');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
</head>
<body>
    <h1>Manage Students</h1>
    <form method="GET" action="addManage_students.php">
        <input type="text" name="search" placeholder="Search students">
        <button type="submit">Search</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Probation Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['student_id']) ?></td>
                <td><?= htmlspecialchars($student['first_name']) . ' ' . htmlspecialchars($student['last_name']) ?></td>
                <td><?= htmlspecialchars($student['student_type']) ?></td>
                <td><?= htmlspecialchars($student['status_on_probation']) ?></td>
                <td>
                    <form method="POST" action="addManage_students.php">
                        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                    <a href="edit_student.php?student_id=<?= urlencode($student['student_id']) ?>">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
