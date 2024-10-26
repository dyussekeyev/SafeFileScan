<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['api_key']) || !isset($data['scan_id']) || !isset($data['verdict'])) {
    echo json_encode(['error' => 'API key, scan ID, and verdict are required']);
    exit();
}

$api_key = htmlspecialchars($data['api_key']);
$scan_id = htmlspecialchars($data['scan_id']);
$verdict = htmlspecialchars($data['verdict']);

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

// Check if the scan record exists and is pending
$stmt = $conn->prepare("SELECT id FROM scans WHERE id = ? AND av_id = ? AND verdict = 'Pending...'");
$stmt->bind_param("ii", $scan_id, $av_id);
$stmt->execute();
$stmt->bind_result($existing_scan_id);
$stmt->fetch();
$stmt->close();

if (!$existing_scan_id) {
    echo json_encode(['error' => 'No pending scan found for the provided scan ID and API key']);
    exit();
}

// Update scan result
$stmt = $conn->prepare("UPDATE scans SET verdict = ?, date_scan = NOW() WHERE id = ? AND av_id = ?");
$stmt->bind_param("sii", $verdict, $scan_id, $av_id);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => 'Scan result updated']);
?>
