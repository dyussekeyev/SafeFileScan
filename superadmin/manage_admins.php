<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';
checkSuperAdminAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    createAdmin($username, $password, $role);
    echo 'Admin created successfully.';
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
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        Role: 
        <select name="role">
            <option value="admin">Admin</option>
            <option value="superadmin">SuperAdmin</option>
        </select><br>
        <input type="submit" value="Create Admin">
    </form>
</body>
</html>
