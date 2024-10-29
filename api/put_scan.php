<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['api_key']) || !isset($data['scan_id']) || !isset($data['verdict']) || !isset($data['hash_value'])) {
    echo json_encode(['error' => 'API key, scan ID, verdict, and hash value (md5, sha1, or sha256) are required']);
    exit();
}

$api_key = htmlspecialchars($data['api_key']);
$scan_id = htmlspecialchars($data['scan_id']);
$verdict = htmlspecialchars($data['verdict']);
$hash_value = htmlspecialchars($data['hash_value']);

// Check API key
$stmt = $conn->prepare("SELECT id FROM avs WHERE api_key = ?");
$stmt->bind_param("s", $api_key);
$stmt->execute();
$stmt->bind_result($av_id);
$stmt->fetch();
$stmt->close();

if (!$av_id) {
    echo json_encode(['error' => 'Invalid API key']);
    exit();
}

// Check if the scan record exists and is pending, and if file_id corresponds to files.id and file.hash_md5, files.hash_sha1 or files.hash_sha256 equals the provided hashe
$stmt = $conn->prepare("SELECT scans.id FROM scans 
                        JOIN files ON scans.file_id = files.id 
                        WHERE scans.id = ? AND scans.av_id = ? AND scans.verdict = 'Pending...' AND (files.hash_md5 = ? OR files.hash_sha1 = ? OR files.hash_sha256 = ?)");
$stmt->bind_param("iis", $scan_id, $av_id, $hash_value);
$stmt->execute();
$stmt->bind_result($existing_scan_id);
$stmt->fetch();
$stmt->close();

if (!$existing_scan_id) {
    echo json_encode(['error' => 'No pending scan found for the provided scan ID, API key, and hash value']);
    exit();
}

// Update scan result
$stmt = $conn->prepare("UPDATE scans SET verdict = ?, date_scan = NOW() WHERE id = ? AND av_id = ?");
$stmt->bind_param("sii", $verdict, $scan_id, $av_id);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => 'Scan result updated']);
?>
