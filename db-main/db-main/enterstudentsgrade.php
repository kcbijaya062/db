<?php
// Establish database connection
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $message = 'Failed to connect to database: ' . oci_error()['message'];
    // Handle connection error here
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from form
    $student_id = $_POST["student_id"];
    $section_id = $_POST["section_id"];
    $grade = $_POST["grade"];

    // Insert data into Enroll table
    $query = "INSERT INTO Enroll (student_id, section_id, grade) VALUES (:student_id, :section_id, :grade)";
    $stmt = oci_parse($connection, $query);

    // Bind parameters
    oci_bind_by_name($stmt, ":student_id", $student_id);
    oci_bind_by_name($stmt, ":section_id", $section_id);
    oci_bind_by_name($stmt, ":grade", $grade);

    // Execute statement
    $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);
    if ($result == false) {
        $error = oci_error($stmt);
        die("Error inserting into Enroll table: " . $error['message']);
    }

    // Commit transaction
    oci_commit($connection);

    // Close statement
    oci_free_statement($stmt);

    echo "Data inserted into Enroll table successfully!";
}

// Function to display Enroll table
function displayEnrollTable() {
    global $connection;
    $query = "SELECT * FROM Enroll";
    $stmt = oci_parse($connection, $query);
    oci_execute($stmt);

    // Display table header
    echo "<h2>Enroll Table</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Student ID</th><th>Section ID</th><th>Grade</th></tr>";

    // Display table rows
    while ($row = oci_fetch_assoc($stmt)) {
        echo "<tr>";
        echo "<td>" . $row['STUDENT_ID'] . "</td>";
        echo "<td>" . $row['SECTION_ID'] . "</td>";
        echo "<td>" . $row['GRADE'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo"Take these data as reference while entering";
    include"displayallfromsections.php";
    include"listallstudents.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Student</title>
</head>
<body>
    <h2>Enroll Student</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="student_id">Student ID:</label><br>
        <input type="text" id="student_id" name="student_id" required><br><br>
        
        <label for="section_id">Section ID:</label><br>
        <input type="text" id="section_id" name="section_id" required><br><br>
        
        <label for="grade">Grade:</label><br>
        <input type="text" id="grade" name="grade"><br><br>
        
        <input type="submit" value="Enroll Student">
    </form>
    <a href="adminhome.php" class="btn">Go back</a>
    <?php displayEnrollTable(); ?>
</body>
</html>
