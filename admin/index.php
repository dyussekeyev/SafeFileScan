<?php
include('../navbar.php');

require_once '../includes/db.php';
require_once '../includes/functions.php';

checkAdminAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_file'])) {
        $fileId = $_POST['file_id'];
        deleteFileInfo($fileId);
        echo 'File info deleted successfully.';
    } else if (isset($_POST['delete_scan'])) {
        $scanId = $_POST['scan_id'];
        deleteScanResult($scanId);
        echo 'Scan result deleted successfully.';
    } else if (isset($_POST['create_admin'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>

<h1>Admin Dashboard</h1>

<h2>Delete File Info</h2>
<form action="index.php" method="post">
    <input type="hidden" name="delete_file" value="1">
    Enter File ID to delete:
    <input type="text" name="file_id">
    <input type="submit" value="Delete File">
</form>

<h2>Delete Scan Result</h2>
<form action="index.php" method="post">
    <input type="hidden" name="delete_scan" value="1">
    Enter Scan ID to delete:
    <input type="text" name="scan_id">
    <input type="submit" value="Delete Scan">
</form>

<h2>Create Admin</h2>
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

<h2>Change Admin Password</h2>
<form action="index.php" method="post">
    <input type="hidden" name="change_password" value="1">
    Username: <input type="text" name="username"><br>
    New Password: <input type="password" name="new_password"><br>
    <input type="submit" value="Change Password">
</form>

</body>
</html>
