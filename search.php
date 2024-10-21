<!DOCTYPE html>
<html>
<head>
    <title>SafeFileScan - Search</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Handle file search
if (isset($_GET['hash'])) {
    $hash = $_GET['hash'];
    $fileInfo = searchFileByHash($hash);
    
    if ($fileInfo) {
        // Display file info in a table
        echo '<h2>File Info</h2>';
        echo '<table border="1">';
        echo '<tr><th>Property</th><th>Value</th></tr>';
        echo '<tr><td>MD5</td><td>' . htmlspecialchars($fileInfo['hash_md5']) . '</td></tr>';
        echo '<tr><td>SHA1</td><td>' . htmlspecialchars($fileInfo['hash_sha1']) . '</td></tr>';
        echo '<tr><td>SHA256</td><td>' . htmlspecialchars($fileInfo['hash_sha256']) . '</td></tr>';
        echo '<tr><td>Size</td><td>' . htmlspecialchars($fileInfo['size']) . '</td></tr>';
        echo '<tr><td>First Upload Date</td><td>' . htmlspecialchars($fileInfo['date_first_upload']) . '</td></tr>';
        echo '<tr><td>Last Analysis Date</td><td>' . htmlspecialchars($fileInfo['date_last_analysis']) . '</td></tr>';
        echo '</table>';

        // Display scan results in a table
        echo '<h2>Scan Results History</h2>';
        $scanResults = getScanResultsByFileId($fileInfo['id']);
        if ($scanResults) {
            echo '<table border="1">';
            echo '<tr><th>Scan Date</th><th>Kaspersky</th><th>Trend Micro</th><th>ESET</th></tr>';
            foreach ($scanResults as $result) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($result['date_scan']) . '</td>';
                echo '<td>' . htmlspecialchars($result['verdict_kaspersky']) . '</td>';
                echo '<td>' . htmlspecialchars($result['verdict_trendmicro']) . '</td>';
                echo '<td>' . htmlspecialchars($result['verdict_eset']) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'No scan results found for this file.';
        }
    } else {
        echo 'No file found with the given hash.';
    }
}
?>

</body>
</html>
