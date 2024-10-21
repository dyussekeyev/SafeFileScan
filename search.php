<!DOCTYPE html>
<html>
<head>
    <title>SafeFileScan - Search</title>
    <style>
        .navbar {
            overflow: hidden;
            background-color: #333;
        }
        .navbar a, .navbar input[type=text], .navbar input[type=password], .navbar input[type=submit] {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            border: none;
            background: none;
        }
        .navbar input[type=text], .navbar input[type=password] {
            background-color: #ddd;
            color: black;
            padding: 6px;
            margin-top: 8px;
            margin-right: 2px;
            margin-left: 2px;
            border-radius: 4px;
        }
        .navbar input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 16px;
            margin-top: 8px;
            margin-right: 2px;
            margin-left: 2px;
            border-radius: 4px;
        }
        .navbar a:hover, .navbar input[type=submit]:hover {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

<?php
include 'includes/db.php';
include 'includes/functions.php';

// Handle file search
if (isset($_GET['hash'])) {
    $hash = $_GET['hash'];
    $fileInfo = searchFileByHash($hash);
    
    if ($fileInfo) {
        // Display file info in a table
        echo '<h2>File Info</h2>';
        echo '<table border="1">';
        echo '<tr><th>Property</th><th>Value</th></tr>';
        echo '<tr><td>Filename</td><td>' . htmlspecialchars($fileInfo['filename']) . '</td></tr>';
        echo '<tr><td>MD5</td><td>' . htmlspecialchars($fileInfo['md5']) . '</td></tr>';
        echo '<tr><td>SHA1</td><td>' . htmlspecialchars($fileInfo['sha1']) . '</td></tr>';
        echo '<tr><td>SHA256</td><td>' . htmlspecialchars($fileInfo['sha256']) . '</td></tr>';
        echo '<tr><td>Imphash</td><td>' . htmlspecialchars($fileInfo['imphash']) . '</td></tr>';
        echo '<tr><td>Size</td><td>' . htmlspecialchars($fileInfo['size']) . '</td></tr>';
        echo '<tr><td>First Upload Date</td><td>' . htmlspecialchars($fileInfo['first_upload_date']) . '</td></tr>';
        echo '<tr><td>Last Analysis Date</td><td>' . htmlspecialchars($fileInfo['last_analysis_date']) . '</td></tr>';
        echo '</table>';

        // Display scan results in a table
        echo '<h2>Scan Results History</h2>';
        $scanResults = getScanResultsByFileId($fileInfo['id']);
        if ($scanResults) {
            echo '<table border="1">';
            echo '<tr><th>Scan Date</th><th>Kaspersky</th><th>Trend Micro</th><th>ESET</th></tr>';
            foreach ($scanResults as $result) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($result['scan_date']) . '</td>';
                echo '<td>' . htmlspecialchars($result['kaspersky_result']) . '</td>';
                echo '<td>' . htmlspecialchars($result['trend_micro_result']) . '</td>';
                echo '<td>' . htmlspecialchars($result['eset_result']) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'No scan results found for this file.';
        }

        // Link to rescan.php
        echo '<h2>Rescan File</h2>';
        echo '<form action="rescan.php" method="post">';
        echo '<input type="hidden" name="file_id" value="' . htmlspecialchars($fileInfo['id']) . '">';
        echo '<input type="submit" value="Rescan">';
        echo '</form>';
    } else {
        echo 'No file found with the given hash.';
    }
}
?>

</body>
</html>
