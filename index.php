<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Checking database connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

// Fetch the 10 most recently uploaded files
$stmt = $conn->prepare("SELECT * FROM files ORDER BY date_first_upload DESC LIMIT 10");
if ($stmt === false) {
    error_log("Prepare failed: " . $conn->error);
    die("Query preparation failed. Please try again later.");
}

if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    die("Query execution failed. Please try again later.");
}

$result = $stmt->get_result();
if ($result === false) {
    error_log("Get result failed: " . $stmt->error);
    die("Fetching results failed. Please try again later.");
}

$files = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SafeFileScan</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <style>
        .menu {
            display: flex;
            background-color: #333;
            padding: 10px;
        }
        .menu a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
        }
        .menu input[type="text"] {
            padding: 7px;
            margin-right: 10px;
        }
        .menu input[type="submit"] {
            padding: 7px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .menu input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="menu">
    <a href="index.php">Home</a>
    <form action="search.php" method="get" style="margin: 0;">
        <input type="text" name="hash" placeholder="Enter hash">
        <input type="submit" value="Search">
    </form>
</div>

<h1>Welcome to SafeFileScan!</h1>

<h2>Upload the file</h2>
<form action="upload.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
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
                <td><a href="search.php?hash=<?php echo htmlspecialchars($file['hash_sha1']); ?>"><?php echo htmlspecialchars($file['hash_sha1']); ?></a></td>
                <td><?php echo htmlspecialchars($file['size']); ?></td>
                <td><?php echo htmlspecialchars($file['date_first_upload']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No files found.</p>
<?php endif; ?>

</body>
</html>
