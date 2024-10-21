<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

// Проверка, что пользователь аутентифицирован
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'superadmin'])) {
    header('Location: index.php');
    exit;
}

// Обработка повторного сканирования
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['file_id'])) {
    $fileId = $_POST['file_id'];
    
    // Получение информации о файле из базы данных
    $stmt = $conn->prepare("SELECT * FROM files WHERE id = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $result = $stmt->get_result();
    $fileInfo = $result->fetch_assoc();
    $stmt->close();
    
    if ($fileInfo) {
        $filePath = 'uploads/' . $fileInfo['filename'];

        // Perform AV scans (pseudo code)
        $results = performAVScans($filePath);

        // Save scan results
        saveScanResults($fileId, $results);

        echo 'Файл успешно повторно просканирован.';
    } else {
        echo 'Файл не найден.';
    }
} else {
    echo 'Неверный запрос.';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SafeFileScan - ReScan</title>
    <style>
        .navbar {
            overflow: hidden;
            background-color: #333;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="login.php">Log in</a>
    </div>
    
    <h1>Rescan the file</h1>
    <form action="rescan.php" method="post">
        ID:
        <input type="text" name="file_id">
        <input type="submit" value="Повторное сканирование">
    </form>
</body>
</html>
