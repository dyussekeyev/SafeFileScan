<?php
include 'includes/db.php';
include 'includes/functions.php';

// Handle file search
if (isset($_GET['hash'])) {
    $hash = $_GET['hash'];
    $fileInfo = searchFileByHash($hash);
    
    if ($fileInfo) {
        // Display file info and scan results
        echo 'File Info:';
        echo '<pre>' . print_r($fileInfo, true) . '</pre>';
        echo 'Scan Results:';
        $scanResults = getScanResults($fileInfo['md5']);
        echo '<pre>' . print_r($scanResults, true) . '</pre>';
    } else {
        echo 'No file found with the given hash.';
    }
}
?>
