<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';
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
<a href="index.php">Delete Scan</a><br>
<a href="index.php">Delete File</a><br>

<h1>Delete File Info</h1>
<form action="index.php" method="post">
    <input type="hidden" name="delete_file" value="1">
    Enter File ID to delete:
    <input type="text" name="file_id">
    <input type="submit" value="Delete File">
</form>

<h1>Delete Scan Result</h1>
<form action="index.php" method="post">
    <input type="hidden" name="delete_scan" value="1">
    Enter Scan ID to delete:
    <input type="text" name="scan_id">
    <input type="submit" value="Delete Scan">
</form>

</body>
</html>
