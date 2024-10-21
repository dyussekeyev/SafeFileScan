<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';
checkAdminAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fileId = $_POST['file_id'];
    deleteFileInfo($fileId);
    echo 'File info deleted successfully.';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete File</title>
</head>
<body>
    <h1>Delete File Info</h1>
    <form action="delete_file.php" method="post">
        Enter File ID to delete:
        <input type="text" name="file_id">
        <input type="submit" value="Delete File">
    </form>
</body>
</html>
