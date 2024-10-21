<?php
include('../navbar.php');

session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

checkSuperAdminAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_admin'])) {
        $username = $_POST['username'];
        $password = $_POST['password');
        $role = $_POST['role'];
        createAdmin($username, $password, $role);
        echo 'Admin created successfully.';
    } else if (isset($_POST['change_password'])) {
        $username = $_POST['username'];
        $newPassword = $_POST['new_password'];
        changeAdminPassword($username, $newPassword);
        echo 'Password changed successfully.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SuperAdmin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>

<h1>SuperAdmin Dashboard</h1>
<a href="index.php">Manage Admins</a><br>
<a href="view_logs.php">View Logs</a><br>

<h1>Create Admin</h1>
<form action="index.php" method="post">
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
<form action="index.php" method="post">
    <input type="hidden" name="change_password" value="1">
    Username: <input type="text" name="username"><br>
    New Password: <input type="password" name="new_password"><br>
    <input type="submit" value="Change Password">
</form>

</body>
</html>
