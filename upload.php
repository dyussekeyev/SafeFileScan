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
    $hash_sha1 = hash_file('sha1', $tempPath); // Corrected algorithm name
    $filePath = 'uploads/' . $hash_sha1;

    // Check if file already exists by hash
    $existingFile = searchFileByHash($hash_sha1);
    if ($existingFile) {
        $fileId = $existingFile['id'];
        echo 'File already exists. Rescanning...';

        // Perform AV scans (pseudo code)
        $results = performAVScans($filePath);

        // Save scan results
        saveScanResults($fileId, $results);

        echo 'File rescanned successfully.';
    } else {
        if (move_uploaded_file($tempPath, $filePath)) { // Ensure filePath is not a directory
            // Calculate other hashes
            $hash_md5 = hash_file('md5', $filePath);
            $hash_sha256 = hash_file('sha256', $filePath);

            // Save file info to database
            $name = $file['name'];
            $type = $file['type'];
            $size = $file['size'];
            $content = file_get_contents($filePath);

            $stmt = $conn->prepare("INSERT INTO files (name, type, size, content) VALUES (?, ?, ?, ?)");
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ssis", $name, $type, $size, $content);
            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }
            $stmt->close();

            // Retrieve the file id
            $fileInfo = searchFileByHash($hash_sha1);
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
