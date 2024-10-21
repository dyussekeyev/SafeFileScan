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
        
        // Выполнение антивирусного сканирования (псевдокод)
        $results = performAVScans($filePath);
        
        // Сохранение результатов сканирования
        saveScanResults($fileInfo['md5'], $results);
        
        echo 'Файл успешно повторно просканирован.';
    } else {
        echo 'Файл не найден.';
    }
} else {
    echo 'Неверный запрос.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Повторное сканирование файла</title>
</head>
<body>
    <h1>Повторное сканирование файла</h1>
    <form action="rescan.php" method="post">
        Введите ID файла для повторного сканирования:
        <input type="text" name="file_id">
        <input type="submit" value="Повторное сканирование">
    </form>
</body>
</html>