<!DOCTYPE html>
<html>
<head>
    <title>SafeFileScan - Загрузка файла</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>

<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Handle file upload and analysis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    $tempPath = $file['tmp_name'];

    if (!empty($tempPath)) {
        try {
            $hashes = getBasicProperties($tempPath);
            $filePath = 'uploads/' . $hashes['sha1'];

            // Check if file already exists by hash
            $existingFile = searchFileByHash($hashes['sha1']);
            if ($existingFile) {
                $fileId = $existingFile['id'];
                echo '<p>File already exists.</p>';

                // Initialize scan
                initScan($fileId);

                echo '<p>Initialized for analysis successfully.</p>';
            } else {
                if (move_uploaded_file($tempPath, $filePath)) {
                    // Save file info to database
                    saveFileInfo($hashes['md5'], $hashes['sha1'], $hashes['sha256'], $hashes['size'], $hashes['file_type']);

                    // Retrieve the file id
                    $fileInfo = searchFileByHash($hashes['sha1']);
                    $fileId = $fileInfo['id'];

                    // Initialize scan
                    initScan($fileId);

                    echo '<p>File uploaded and initialized for analysis successfully.</p>';
                } else {
                    echo '<p>File upload failed.</p>';
                }
            }
        } catch (ValueError $e) {
            echo 'Error: ' . htmlspecialchars($e->getMessage());
        }
    } else {
        echo '<p>File upload failed: temporary path is empty.</p>';
    }
}
?>
</body>
</html>
