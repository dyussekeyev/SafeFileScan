<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';
checkAdminAuth();

echo '<h1>Admin Dashboard</h1>';
echo '<a href="delete_scan.php">Delete Scan</a><br>';
echo '<a href="delete_file.php">Delete File</a><br>';
?>
