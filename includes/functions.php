function saveScanResults($file_id, $results) {
    global $conn;
    
    // Insert scan results
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
    
    // Update date_last_analysis in files table
    $updateStmt = $conn->prepare("UPDATE files SET date_last_analysis = NOW() WHERE id = ?");
    if ($updateStmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $updateStmt->bind_param("i", $file_id);
    $updateStmt->execute();
    if ($updateStmt->error) {
        die('Execute failed: ' . htmlspecialchars($updateStmt->error));
    }
    $updateStmt->close();
}
