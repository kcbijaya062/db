<?php
session_start();
if (!isset($_SESSION['admin_username'])) { 
    header("Location: adminlogin.php");
    exit;
}

echo "<h1>Welcome, Admin</h1>";

// Change Password Form
echo "<h2>Change Password</h2>";
echo "<form method='post' action=''>";
echo "<input type='hidden' name='action' value='change_password'/>";
echo "New Password: <input type='password' name='new_password' required/>";
echo "<input type='submit' value='Change Password'/>";
echo "</form>";

// Add, Delete, Update User Form
echo "<h2>User Management</h2>";
// Assuming you implement these actions in the same script or in separate ones
echo "<form method='post' action='manage_users.php'>";
echo "Username: <input type='text' name='username'/>";
echo "Password: <input type='password' name='password'/>";
echo "First Name: <input type='text' name='first_name'/>";
echo "Last Name: <input type='text' name='last_name'/>";
echo "<input type='submit' name='action' value='Add User'/>";
echo "<input type='submit' name='action' value='Delete User'/>";
echo "<input type='submit' name='action' value='Update User'/>";
echo "</form>";

// Further functionality for listing or searching users can be added as needed
?>
