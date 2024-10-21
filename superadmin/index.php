<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';
checkSuperAdminAuth();

echo '<h1>SuperAdmin Dashboard</h1>';
echo '<a href="manage_admins.php">Manage Admins</a><br>';
echo '<a href="view_logs.php">View Logs</a><br>';
?>
