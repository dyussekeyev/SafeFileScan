<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';
checkSuperAdminAuth();

$logs = getEventLogs();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Logs</title>
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
            <td><?php echo $log['id']; ?></td>
            <td><?php echo $log['user_id']; ?></td>
            <td><?php echo $log['event_type']; ?></td>
            <td><?php echo $log['event_description']; ?></td>
            <td><?php echo $log['event_date']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
