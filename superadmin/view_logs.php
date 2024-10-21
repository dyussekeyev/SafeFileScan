<?php
include('../navbar.php');

session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

checkSuperAdminAuth();

$logs = getEventLogs();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Logs</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>

<h1>Event Logs</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Event Type</th>
        <th>Event Description</th>
        <th>Event Date</th>
    </tr>
    <?php foreach ($logs as $log) { ?>
    <tr>
        <td><?php echo htmlspecialchars($log['id']); ?></td>
        <td><?php echo htmlspecialchars($log['user_id']); ?></td>
        <td><?php echo htmlspecialchars($log['event_type']); ?></td>
        <td><?php echo htmlspecialchars($log['event_description']); ?></td>
        <td><?php echo htmlspecialchars($log['event_date']); ?></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
