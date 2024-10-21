<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';
checkSuperAdminAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_admin'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];
        createAdmin($username, $password, $role);
        echo 'Admin created successfully.';
    } else if (isset($_POST['change_password'])) {
        $username = $_POST['username'];
        $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        changeAdminPassword($username, $newPassword);
        echo 'Password changed successfully.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins</title>
</head>
<body>
    <h1>Create Admin</h1>
    <form action="manage_admins.php" method="post">
        <input type="hidden" name="create_admin" value="1">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        Role: 
        <select name="role">
            <option value="admin">Admin</option>
            <option value="superadmin">SuperAdmin</option>
        </select><br>
        <input type="submit" value="Create Admin">
    </form>

    <h1>Change Admin Password</h1>
    <form action="manage_admins.php" method="post">
        <input type="hidden" name="change_password" value="1">
        Username: <input type="text" name="username"><br>
        New Password: <input type="password" name="new_password"><br>
        <input type="submit" value="Change Password">
    </form>
</body>
</html>
