<?php
function saveFileInfo($filename, $md5, $sha1, $sha256, $imphash, $size) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO files (filename, md5, sha1, sha256, imphash, size, first_upload_date, last_analysis_date) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sssssi", $filename, $md5, $sha1, $sha256, $imphash, $size);
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

function saveScanResults($md5, $results) {
    global $conn;
    foreach ($results as $antivirus => $result) {
        $stmt = $conn->prepare("INSERT INTO scan_results (file_md5, antivirus_name, scan_date, result) VALUES (?, ?, NOW(), ?)");
        $stmt->bind_param("sss", $md5, $antivirus, $result);
        $stmt->execute();
        $stmt->close();
    }
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

function getScanResults($md5) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM scan_results WHERE file_md5 = ?");
    $stmt->bind_param("s", $md5);
    $stmt->execute();
    $result = $stmt->get_result();
    $scanResults = [];
    while ($row = $result->fetch_assoc()) {
        $scanResults[] = $row;
    }
    $stmt->close();
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