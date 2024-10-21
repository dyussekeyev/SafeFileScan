<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';
checkAdminAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scanId = $_POST['scan_id'];
    deleteScanResult($scanId);
    echo 'Scan result deleted successfully.';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Scan</title>
</head>
<body>
    <h1>Delete Scan Result</h1>
    <form action="delete_scan.php" method="post">
        Enter Scan ID to delete:
        <input type="text" name="scan_id">
        <input type="submit" value="Delete Scan">
    </form>
</body>
</html>
