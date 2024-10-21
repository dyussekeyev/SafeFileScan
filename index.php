<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

// Check if the user is logging out
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Fetch the 10 most recently uploaded files
$stmt = $conn->prepare("SELECT filename, md5, size, first_upload_date FROM files ORDER BY first_upload_date DESC LIMIT 10");
$stmt->execute();
$result = $stmt->get_result();
$files = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SafeFileScan</title>
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
        <?php if (isset($_SESSION['username'])): ?>
            <a href="#">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <a href="index.php?logout=true">Log out</a>
        <?php else: ?>
            <a href="login.php">Log in</a>
        <?php endif; ?>
    </div>
    
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
                <th>Upload Date</th>
                <th>MD5</th>
                <th>Size</th>
            </tr>
            <?php foreach ($files as $file): ?>
                <tr>
                    <td><?php echo htmlspecialchars($file['first_upload_date']); ?></td>
                    <td><a href="search.php?hash=<?php echo htmlspecialchars($file['md5']); ?>"><?php echo htmlspecialchars($file['md5']); ?></a></td>
                    <td><?php echo htmlspecialchars($file['size']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No files found.</p>
    <?php endif; ?>
</body>
</html>
