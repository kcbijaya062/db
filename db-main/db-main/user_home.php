<?php
// Start or resume the session
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username']; // Retrieve the username from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
</head>
<body>
    <h1>Welcome, User or  <?php echo htmlspecialchars($username); ?> User</h1>
    <p>This is the user home page.</p>
    <p>  .  Part Two involves designing and implementing a “Student Enrollment Information System” for these users.  
        The completed enrollment system will allow a student user to query his/her course information as well
         as allow them to enroll in courses through a web interface.  Administrators of
         the system will be able to manage and maintain
          the student enrollment information system through a web interface.</p>

          <h3> change your own password</h3>
          <button type="button" class="btn btn-outline-danger"><a href="changepasswordforuser.php" target="_blank">Change password</a></button>
    <br>
    <a href="logout.php">Logout</a><br>
    <a href="student_personalInfo_page.php">student personal Info pag</a><br>
    <a href="student_academicInfo_page.php">student_academicInfo</a><br>
    <a href="student_EnrollInfoPage.php">student_Enrollment_page</a><br>
    <a href="studentenrollmentview.php" class="btn">Student Enrollment view</a>
    <p> If Student- Admin user then go to <a href="adminlogin.php">Student Admin User login</a><br>
    <p> If Student user then go to <a href="userlogin.php">Student User login</a><br>
</body>
</html>
