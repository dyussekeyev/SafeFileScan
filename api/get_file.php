<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['api_key']) || !isset($data['scan_id'])) {
    echo json_encode(['error' => 'API key and scan ID are required']);
    exit();
}

$api_key = htmlspecialchars($data['api_key']);
$scan_id = htmlspecialchars($data['scan_id']);

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

// Fetch file name
$stmt = $conn->prepare("SELECT f.hash_sha1 FROM files f JOIN scans s ON f.id = s.file_id WHERE s.id = ? AND s.av_id = ?");
$stmt->bind_param("ii", $scan_id, $av_id);
$stmt->execute();
$stmt->bind_result($file_hash);
$stmt->fetch();
$stmt->close();

if (!$file_hash) {
    echo json_encode(['error' => 'Invalid scan ID']);
    exit();
}

$file_path = "../uploads/$file_hash";
if (!file_exists($file_path)) {
    echo json_encode(['error' => 'File not found']);
    exit();
}

// Initiate file download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));
flush();
readfile($file_path);
exit();
?>
