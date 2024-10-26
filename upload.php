<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Checking database connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SafeFileScan - File upload</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>

<div class="menu">
    <a href="index.php">Home</a>
    <form action="search.php" method="get" style="margin: 0;">
        <input type="text" name="hash" placeholder="Enter hash">
        <input type="submit" value="Search">
    </form>
</div>

<h1>File uploading...</h1>
    
<?php
// Handle file upload and analysis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    $tempPath = $file['tmp_name'];

    if (!empty($tempPath)) {
        try {
            $hashes = getBasicProperties($tempPath);
            $filePath = '../uploads/' . $hashes['sha1'];

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
            echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    } else {
        echo '<p>File upload failed: temporary path is empty.</p>';
    }
}
?>
</body>
</html>
