<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';
checkSuperAdminAuth();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SuperAdmin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>

<h1>SuperAdmin Dashboard</h1>
<a href="manage_admins.php">Manage Admins</a><br>
<a href="view_logs.php">View Logs</a><br>

</body>
</html>
