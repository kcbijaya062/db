<?php
session_start();
// must be userloginas student
if (!isset($_SESSION['username']) || (isset($_SESSION['is_admin']) && $_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}

echo "<h1>Welcome to the Student Home Page</h1>";
echo "<p>Hello, " . htmlspecialchars($_SESSION['username']) . "! Here are your available actions:</p>";
echo "<ul>";
echo "<li><a href='view_courses.php'>View Available Courses</a></li>";
echo "<li><a href='view_enrollments.php'>View My Enrollments</a></li>";
echo "<li><a href='change_password.php'>Change My Password</a></li>";
echo "</ul>";
echo "<form action="change_password.php" method="post">
<input type="password" name="current_password" placeholder="Current Password" required>
<input type="password" name="new_password" placeholder="New Password" required>
<button type="submit">Change Password</button>
</form>
"
// Logout link
echo "<p><a href='logout.php'>Logout</a></p>";
?>

