<!DOCTYPE html>
<html>
<head>
    <title>SafeFileScan - Загрузка файла</title>
    <style>
        .navbar {
            overflow: hidden;
            background-color: #333;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="upload.php">Upload</a>
        <a href="scan_results.php">Scan Results</a>
        <a href="admin.php">Admin</a>
    </div>

<?php
include 'includes/db.php';
include 'includes/functions.php';

// Handle file upload and analysis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    $tempPath = $file['tmp_name'];
    $sha1 = hash_file('sha1', $tempPath);
    $filePath = 'uploads/' . $sha1;

    // Check if file already exists by hash
    $existingFile = searchFileByHash($sha1);
    if ($existingFile) {
        echo 'File already exists.';
    } else {
        if (move_uploaded_file($tempPath, $filePath)) {
            // Calculate other hashes
            $md5 = hash_file('md5', $filePath);
            $sha256 = hash_file('sha256', $filePath);
            $imphash = ''; // Calculate imphash if applicable

            // Save file info to database
            saveFileInfo($sha1, $md5, $sha1, $sha256, $imphash, $file['size']);

            // Perform AV scans (pseudo code)
            $results = performAVScans($filePath);

            // Save scan results
            saveScanResults($md5, $results);

            echo 'File uploaded and analyzed successfully.';
        } else {
            echo 'File upload failed.';
        }
    }
}
?>

</body></html>
