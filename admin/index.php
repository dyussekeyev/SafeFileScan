<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';
checkAdminAuth();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>

<h1>Admin Dashboard</h1>
<a href="delete_scan.php">Delete Scan</a><br>
<a href="delete_file.php">Delete File</a><br>

</body>
</html>
