<?php
include('../navbar.php');

session_start();
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

</body>
</html>
