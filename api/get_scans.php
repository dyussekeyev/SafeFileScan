<?php
require_once '../includes/db.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['api_key'])) {
    echo json_encode(['error' => 'API key is required']);
    exit();
}

$api_key = htmlspecialchars($data['api_key']);

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

// Fetch pending scans
$stmt = $conn->prepare("SELECT id FROM scans WHERE av_id = ? AND verdict = 'Pending...'");
$stmt->bind_param("i", $av_id);
$stmt->execute();
$result = $stmt->get_result();
$scans = [];
while ($row = $result->fetch_assoc()) {
    $scans[] = $row['id'];
}
$stmt->close();

echo json_encode(['scans' => $scans]);
?>
