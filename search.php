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

        // Display scan results in a single table
        echo '<h2>Scan Results</h2>';
        $scanResults = getScanResults($fileInfo['md5']);
        if ($scanResults) {
            echo '<table border="1">';
            echo '<tr><th>Antivirus Name</th><th>Result</th></tr>';
            echo '<tr><td>Kaspersky</td><td>' . htmlspecialchars($scanResults['kaspersky_result']) . '</td></tr>';
            echo '<tr><td>Trend Micro</td><td>' . htmlspecialchars($scanResults['trend_micro_result']) . '</td></tr>';
            echo '<tr><td>ESET</td><td>' . htmlspecialchars($scanResults['eset_result']) . '</td></tr>';
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
