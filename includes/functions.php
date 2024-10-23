<?php
function getBasicProperties($filePath) {
    if (empty($filePath)) {
        throw new ValueError("Path cannot be empty");
    }

    $fileType = shell_exec("file -b " . escapeshellarg($filePath));
    if ($fileType === null) {
        die('Error executing file command.');
    }
    return [
        'md5' => hash_file('md5', $filePath),
        'sha1' => hash_file('sha1', $filePath),
        'sha256' => hash_file('sha256', $filePath),
        'size' => filesize($filePath),
        'file_type' => trim($fileType) // Trim any trailing newline
    ];
}

function saveFileInfo($hash_md5, $hash_sha1, $hash_sha256, $size, $file_type) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO files (hash_md5, hash_sha1, hash_sha256, size, file_type, date_first_upload) VALUES (?, ?, ?, ?, ?, NOW())");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("sssis", $hash_md5, $hash_sha1, $hash_sha256, $size, $file_type);
    $stmt->execute();
    if ($stmt->error) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}

function initScan($file_id) {
    global $conn;

    // Enumerate rows from the avs table
    $result = $conn->query("SELECT id FROM avs");
    if ($result === false) {
        die('Query failed: ' . htmlspecialchars($conn->error));
    }

    // Iterate over each AV and create a scan record
    while ($row = $result->fetch_assoc()) {
        $av_id = $row['id'];
        $stmt = $conn->prepare("INSERT INTO scans (file_id, av_id, verdict, date_scan) VALUES (?, ?, 'Pending...', NOW())");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("ii", $file_id, $av_id);
        $stmt->execute();
        if ($stmt->error) {
            die('Execute failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
    }
}

function searchFileByHash($hash) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM files WHERE hash_md5 = ? OR hash_sha1 = ? OR hash_sha256 = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("sss", $hash, $hash, $hash);
    $stmt->execute();
    if ($stmt->error) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $result = $stmt->get_result();
    $fileInfo = $result->fetch_assoc();
    $stmt->close();
    return $fileInfo;
}

function getScanResultsByFileId($fileId) {
    global $conn; // Assuming $conn is your database connection

    $stmt = $conn->prepare("SELECT id, verdict, date_scan FROM scans WHERE file_id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    if ($stmt->error) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $result = $stmt->get_result();
    $scanResults = [];

    while ($row = $result->fetch_assoc()) {
        $scanResults[] = $row;
    }

    $stmt->close();
    return $scanResults;
}

function checkAdminAuth() {
    if (!isset($_SESSION['logged'])) {
        header('Location: ../index.php');
        exit;
    }
}

function createAdmin($username, $password, $role) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO admins (username, password, date_last_logon) VALUES (?, ?, NOW())");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("sss", $username, $password);
    $stmt->execute();
    if ($stmt->error) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}

function changeAdminPassword($username, $newPassword) {
    global $conn;
    $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE username = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ss", $newPassword, $username);
    $stmt->execute();
    if ($stmt->error) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}

function deleteScanResult($scanId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM scans WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $scanId);
    $stmt->execute();
    if ($stmt->error) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}

function deleteFileInfo($fileId) {
    global $conn;

    // Delete related scan results
    $stmt = $conn->prepare("DELETE FROM scans WHERE file_id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    if ($stmt->error) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();

    // Delete the file record
    $stmt = $conn->prepare("DELETE FROM files WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    if ($stmt->error) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();
}
?>
