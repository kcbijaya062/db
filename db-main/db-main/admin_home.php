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

function executeQuery($connection, $query, $binds = [], $fetchAll = false) {
    $stmt = oci_parse($connection, $query);
    foreach ($binds as $placeholder => $value) {
        oci_bind_by_name($stmt, $placeholder, $value);
    }
    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error' => $error['message']];
    }
    if ($fetchAll) {
        $data = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $data[] = $row;
        }
        return $data;
    }
    return true;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'Search':
            $search = '%' . $_POST['search'] . '%';
            $users = executeQuery($connection, "SELECT username, firstname, lastname FROM UserAccountData WHERE LOWER(username) LIKE LOWER(:search)", [":search" => $search], true);
            $displayUsers = true;
            break;

        case 'List Users':
            $users = executeQuery($connection, "SELECT username, firstname, lastname FROM UserAccountData", [], true);
            $displayUsers = true;
            break;

        case 'Add User':
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $result = executeQuery($connection, "INSERT INTO UserAccountData (username, password, firstname, lastname) VALUES (:username, :password, :firstname, :lastname)", [
                ":username" => $_POST['username'],
                ":password" => $password,
                ":firstname" => $_POST['firstname'],
                ":lastname" => $_POST['lastname']
            ]);
            $message = $result === true ? "User added successfully" : "Failed to add user: " . $result['error'];
            break;

        case 'Delete User':
            $result = executeQuery($connection, "DELETE FROM UserAccountData WHERE username = :username", [":username" => $_POST['username']]);
            $message = $result === true ? "User deleted successfully" : "Failed to delete user";
            break;

        case 'Update User':
            $result = executeQuery($connection, "UPDATE UserAccountData SET firstname = :firstname, lastname = :lastname WHERE username = :username", [
                ":firstname" => $_POST['firstname'],
                ":lastname" => $_POST['lastname'],
                ":username" => $_POST['username']
            ]);
            $message = $result === true ? "User updated successfully" : "Failed to update user";
            break;

        case 'Change Password':
            // Verify the current password first
            $userData = executeQuery($connection, "SELECT password FROM UserAccountData WHERE username = :username", [":username" => $_SESSION['admin_username']], true);
            if ($userData && password_verify($_POST['current_password'], $userData[0]['PASSWORD'])) {
                $hashedNewPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $result = executeQuery($connection, "UPDATE UserAccountData SET password = :newPassword WHERE username = :username", [
                    ":newPassword" => $hashedNewPassword,
                    ":username" => $_SESSION['admin_username']
                ]);
                $message = $result === true ? "Password changed successfully." : "Failed to change password.";
            } else {
                $message = "Current password is incorrect.";
            }
            break;
    }
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Admin Dashboard</h1>
    <p><?php echo $message; ?></p>

    <!-- Search and List Form -->
    <form method="post">
        <input type="hidden" name="action" value="Search">
        <input type="text" name="search" placeholder="Search users...">
        <button type="submit">Search</button>
        <button type="submit" name="action" value="List Users">List All Users</button>
    </form>

    <!-- Add User Form -->
    <h2>Add User</h2>
    <form action="" method="post">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required>
        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Submit</button>
    </form>

    <!-- Update User Form -->
    <h2>Update User</h2>
    <form method="post">
        <input type="hidden" name="action" value="Update User">
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="firstname" placeholder="First Name">
        <input type="text" name="lastname" placeholder="Last Name">
        <button type="submit">Update</button>
    </form>

    <!-- Delete User Form -->
    <h2>Delete User</h2>
    <form method="post">
        <input type="hidden" name="action" value="Delete User">
        <input type="text" name="username" placeholder="Username" required>
        <button type="submit">Delete</button>
    </form>

    <!-- Change Password Form -->
    <h2>Change Your Password</h2>
    <form method="post">
        <input type="hidden" name="action" value="Change Password">
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <button type="submit">Change Password</button>
    </form>
     <div>
     <a href="addManage_students.php">Add orManage Students page</a><br>
     <a href="editEnter_Grade_page.php">Edit or Enter Students grades</a><br>
    </div>






    <!-- User List -->
    <?php if ($displayUsers && !empty($users)): ?>
    <h2>User List</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
 

</body>
</html>
