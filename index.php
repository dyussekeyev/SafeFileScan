<?php
include 'includes/db.php';
include 'includes/functions.php';

// Fetch the 10 most recently uploaded files
$stmt = $conn->prepare("SELECT filename, md5, sha1, sha256, first_upload_date FROM files ORDER BY first_upload_date DESC LIMIT 10");
$stmt->execute();
$result = $stmt->get_result();
$files = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VirusTotal Clone</title>
</head>
<body>
    <h1>Welcome to VirusTotal Clone</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="submit">
    </form>
    <form action="search.php" method="get">
        Search by Hash:
        <input type="text" name="hash" placeholder="md5, sha1, or sha256">
        <input type="submit" value="Search">
    </form>
    
    <h2>Last 10 Uploaded Files</h2>
    <?php if (!empty($files)): ?>
        <table border="1">
            <tr>
                <th>Filename</th>
                <th>MD5</th>
                <th>SHA1</th>
                <th>SHA256</th>
                <th>First Upload Date</th>
            </tr>
            <?php foreach ($files as $file): ?>
                <tr>
                    <td><?php echo htmlspecialchars($file['filename']); ?></td>
                    <td><?php echo htmlspecialchars($file['md5']); ?></td>
                    <td><?php echo htmlspecialchars($file['sha1']); ?></td>
                    <td><?php echo htmlspecialchars($file['sha256']); ?></td>
                    <td><?php echo htmlspecialchars($file['first_upload_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No files found.</p>
    <?php endif; ?>
</body>
</html>
