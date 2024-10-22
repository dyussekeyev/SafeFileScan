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
    $hashes = getBasicProperties($tempPath);
    $filePath = 'uploads/' . $hashes['sha1'];

    // Check if file already exists by hash
    $existingFile = searchFileByHash($hashes['sha1']);
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
            // Save file info to database
            saveFileInfo($hashes['md5'], $hashes['sha1'], $hashes['sha256'], $hashes['size'], $hashes['file_type']);

            // Retrieve the file id
            $fileInfo = searchFileByHash($hashes['sha1']);
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
