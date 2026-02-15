<?php
session_start();
if (!isset($_SESSION['username']) || (!$_SESSION['is_admin'] && !isset($_SESSION['student_admin']))) {
    header("Location: login.php");
    exit;
}

echo "<h1>Welcome to the Student Admin Home Page</h1>";
echo "<p>Hello, " . htmlspecialchars($_SESSION['username']) . "!</p>";

echo "<h2>Admin Actions</h2>";
echo "<ul>";
echo "<li><a href='admin_home.php'>Admin Home</a></li>";
echo "<li><a href='user_home.php'>User Home page</a></li>";
echo "</ul>";

echo "<h2>Student Actions</h2>";
echo "<ul>";
echo "<li><a href='user_home.php'>Student Home</a></li>";
echo "<li><a href='view_courses.php'>View Courses</a></li>";
echo "<li><a href='enroll_course.php'>Enroll in a Course</a></li>";
echo "</ul>";

echo "<p><a href='logout.php'>Logout</a></p>";
?>
