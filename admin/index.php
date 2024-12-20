<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Checking database connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

// Start the session
session_start();

// Check if the user is logging in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login']) && isset($_POST['password'])) {
    $username = $_POST['login'];
    $password = $_POST['password'];

    // Validate user credentials
    $stmt = $conn->prepare("SELECT username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($db_username, $db_password);
    $stmt->fetch();

    if ($db_username && password_verify($password, $db_password)) {
        $_SESSION['username'] = $db_username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid login credentials.";
    }
    $stmt->close();
}

// Check if the user is logging out
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>

<div class="menu">
    <a href="../index.php">Home</a>
    <a href="./index.php?logout=true">Log out</a>
    <form action="../search.php" method="get" style="margin: 0;">
        <input type="text" name="hash" placeholder="Enter hash">
        <input type="submit" value="Search">
    </form>
</div>

<h1>File search</h1>

<?php
// Check if user is authenticated
if (!isset($_SESSION['username'])) { // Display the login form if the user is not authenticated
?>

<h1>Admin Login</h1>
<?php if (isset($error)) echo '<p style="color:red;">' . $error . '</p>'; ?>
<form method="post" action="index.php">
    Username: <input type="text" name="login" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Log in">
</form>

<?php
    exit();
}
else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['delete_file'])) {
            $fileId = $_POST['file_id'];
            deleteFileInfo($fileId);
            echo 'File info deleted successfully.';
        } else if (isset($_POST['delete_scan'])) {
            $scanId = $_POST['scan_id'];
            deleteScanResult($scanId);
            echo 'Scan result deleted successfully.';
        }
    }
?>

<h1>Admin Dashboard</h1>

<h2>Delete File Info</h2>
<form action="index.php" method="post">
    <input type="hidden" name="delete_file" value="1">
    Enter File ID to delete:
    <input type="text" name="file_id">
    <input type="submit" value="Delete File">
</form>

<h2>Delete Scan Result</h2>
<form action="index.php" method="post">
    <input type="hidden" name="delete_scan" value="1">
    Enter Scan ID to delete:
    <input type="text" name="scan_id">
    <input type="submit" value="Delete Scan">
</form>
    
<?php
}
?>

</body>
</html>
