<!DOCTYPE html>
<html>
<head>
    <title>SafeFileScan - Загрузка файла</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Handle file upload and analysis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    $tempPath = $file['tmp_name'];
    $sha1 = hash_file('sha1', $tempPath);
    $filePath = 'uploads/' . $sha1;

    // Check if file already exists by hash
    $existingFile = searchFileByHash($sha1);
    if ($existingFile) {
        $fileId = $existingFile['id'];
        echo 'File already exists. Rescanning...';

        // Perform AV scans (pseudo code)
        $results = performAVScans($filePath);

        // Save scan results
        saveScanResults($fileId, $results);

        echo 'File rescanned successfully.';
    } else {
        if (move_uploaded_file($tempPath, $filePath)) {
            // Calculate other hashes
            $md5 = hash_file('md5', $filePath);
            $sha256 = hash_file('sha256', $filePath);
            $imphash = ''; // Calculate imphash if applicable

            // Save file info to database
            saveFileInfo($sha1, $md5, $sha1, $sha256, $imphash, $file['size']);

            // Retrieve the file id
            $fileInfo = searchFileByHash($sha1);
            $fileId = $fileInfo['id'];

            // Perform AV scans (pseudo code)
            $results = performAVScans($filePath);

            // Save scan results
            saveScanResults($fileId, $results);

            echo 'File uploaded and analyzed successfully.';
        } else {
            echo 'File upload failed.';
        }
    }
}
?>
</body>
</html>
