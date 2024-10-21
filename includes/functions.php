<?php
function saveFileInfo($hash_md5, $hash_sha1, $hash_sha256, $size) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO files (hash_md5, hash_sha1, hash_sha256, size, first_upload_date, last_analysis_date) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sssi", $hash_md5, $hash_sha1, $hash_sha256, $size);
    $stmt->execute();
    $stmt->close();
}

function performAVScans($filePath) {
    // This is a placeholder function. Integrate actual AV scan logic.
    return [
        'Kaspersky' => 'Clean',
        'Trend Micro' => 'Clean',
        'ESET' => 'Clean'
    ];
}

function saveScanResults($file_id, $results) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO scan_results (file_id, verdict_kaspersky, verdict_trendmicro, verdict_eset, date_scan) VALUES (?, ?, ?, ?, NOW())");

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("isss", $file_id, $results['Kaspersky'], $results['Trend Micro'], $results['ESET']);
    $stmt->execute();

    if ($stmt->error) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }

    $stmt->close();
}

function searchFileByHash($hash) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM files WHERE md5 = ? OR sha1 = ? OR sha256 = ?");
    $stmt->bind_param("sss", $hash, $hash, $hash);
    $stmt->execute();
    $result = $stmt->get_result();
    $fileInfo = $result->fetch_assoc();
    $stmt->close();
    return $fileInfo;
}

function getScanResultsByFileId($fileId) {
    global $conn; // Assuming $conn is your database connection

    $stmt = $conn->prepare("SELECT verdict_kaspersky, verdict_trendmicro, verdict_eset, date_scan FROM scan_results WHERE file_id = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $result = $stmt->get_result();
    $scanResults = [];

    while ($row = $result->fetch_assoc()) {
        $scanResults[] = $row;
    }

    return $scanResults;
}

function checkAdminAuth() {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
        header('Location: ../index.php');
        exit;
    }
}

function checkSuperAdminAuth() {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'superadmin') {
        header('Location: ../index.php');
        exit;
    }
}

function createAdmin($username, $password, $role) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
    $stmt->close();
}

function changeAdminPassword($username, $newPassword) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $newPassword, $username);
    $stmt->execute();
    $stmt->close();
}

function deleteScanResult($scanId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM scan_results WHERE id = ?");
    $stmt->bind_param("i", $scanId);
    $stmt->execute();
    $stmt->close();
}

function deleteFileInfo($fileId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM files WHERE id = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $stmt->close();
}

function getEventLogs() {
    global $conn;
    $result = $conn->query("SELECT * FROM event_logs");
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
    return $logs;
}
?>
