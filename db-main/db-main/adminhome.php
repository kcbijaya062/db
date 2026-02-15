<?php
session_start();

// Redirect if not logged in as an admin
if (!isset($_SESSION['admin_username'])) {
    header("Location: adminlogin.php");
    exit;
}
$message = "";
$users = [];
$displayUsers = false; // Control display of the user table

// Establish a database connection
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    $message = 'Failed to connect to database: ' . oci_error()['message'];

}
oci_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            background-color: #f2f2f2; /* Secondary background color */
        }
        /* Additional CSS styles */
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Admin Dashboard</h1>
        <p><?php echo $message; ?></p>
        
        <!-- Clickable links for actions -->
        <a href="displayusers.php" class="btn">List All Users</a>

        <a href="searchusers.php" class="btn">Search Users</a>
        <a href="insertoraddusers.php" class="btn">Add User</a>
        <a href="updateusers.php" class="btn">Update User</a>
        <a href="deleteusers.php" class="btn">Delete User</a>
        <a href="change_password.php" class="btn">Change Password</a><br><br>
        <a href="index.html" class="btn">Go back</a>
           </div>
           <div class="container">
           <a href="addstudents.php" class="btn">Add Students </a>
           <a href="updatestudents.php" class="btn">Updata Students</a>
           <a href="deletestudents.php" class="btn">delete Students</a>
           <a href="listallstudents.php" class="btn">list all Students </a>
           <a href="enterstudentsgrade.php" class="btn">Enter students Grade here  </a><br><br>
           <a href="displayallfromcourse.php" class="btn">Course Lists</a>
           <a href="upgradegrade.php" class="btn">Upgrade grade with stored procedure here</a>
    </div>
           
</body>
</html>

 



