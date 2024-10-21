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
        echo '<tr><th>Filename</th><th>MD5</th><th>SHA1</th><th>SHA256</th><th>Imphash</th><th>Size</th><th>First Upload Date</th><th>Last Analysis Date</th></tr>';
        echo '<tr>';
        echo '<td>' . htmlspecialchars($fileInfo['filename']) . '</td>';
        echo '<td>' . htmlspecialchars($fileInfo['md5']) . '</td>';
        echo '<td>' . htmlspecialchars($fileInfo['sha1']) . '</td>';
        echo '<td>' . htmlspecialchars($fileInfo['sha256']) . '</td>';
        echo '<td>' . htmlspecialchars($fileInfo['imphash']) . '</td>';
        echo '<td>' . htmlspecialchars($fileInfo['size']) . '</td>';
        echo '<td>' . htmlspecialchars($fileInfo['first_upload_date']) . '</td>';
        echo '<td>' . htmlspecialchars($fileInfo['last_analysis_date']) . '</td>';
        echo '</tr>';
        echo '</table>';

        // Display scan results in multiple tables
        echo '<h2>Scan Results</h2>';
        $scanResults = getScanResults($fileInfo['md5']);
        if ($scanResults) {
            foreach ($scanResults as $result) {
                echo '<table border="1">';
                echo '<tr><th>Antivirus Name</th><th>Scan Date</th><th>Result</th></tr>';
                echo '<tr>';
                echo '<td>' . htmlspecialchars($result['antivirus_name']) . '</td>';
                echo '<td>' . htmlspecialchars($result['scan_date']) . '</td>';
                echo '<td>' . htmlspecialchars($result['result']) . '</td>';
                echo '</tr>';
                echo '</table><br>';
            }
        } else {
            echo 'No scan results found for this file.';
        }
    } else {
        echo 'No file found with the given hash.';
    }
}
?>
