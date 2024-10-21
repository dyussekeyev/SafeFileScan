<?php
include 'navbar.php';

// Fetch the 10 most recently uploaded files
$stmt = $conn->prepare("SELECT hash_sha1, size, first_upload_date FROM files ORDER BY first_upload_date DESC LIMIT 10");
$stmt->execute();
$result = $stmt->get_result();
$files = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SafeFileScan</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>

<h1>Welcome to SafeFileScan!</h1>

<h2>Upload the file</h2>
<form action="upload.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>

<h2>Search the file</h2>
<form action="search.php" method="get">
    Search by Hash:
    <input type="text" name="hash" placeholder="md5, sha1, or sha256">
    <input type="submit" value="Search">
</form>

<h2>Last 10 Uploaded Files</h2>
<?php if (!empty($files)): ?>
    <table border="1">
        <tr>
            <th>SHA1</th>
            <th>Size</th>
            <th>Upload Date</th>
        </tr>
        <?php foreach ($files as $file): ?>
            <tr>
                <td><a href="search.php?hash=<?php echo htmlspecialchars($file['sha1']); ?>"><?php echo htmlspecialchars($file['sha1']); ?></a></td>
                <td><?php echo htmlspecialchars($file['size']); ?></td>
                <td><?php echo htmlspecialchars($file['first_upload_date']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No files found.</p>
<?php endif; ?>
</body>
</html>
