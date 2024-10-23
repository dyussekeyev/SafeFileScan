<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if the user is logging in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login']) && isset($_POST['password'])) {
    $username = '';

    // Validate user credentials
    $stmt = $conn->prepare("SELECT username FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $_POST['login'], $_POST['password']);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();

    if ($username == $_POST['login']) {
        $_SESSION['username'] = $username;
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

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    // Display the login form if the user is not authenticated
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <link rel="stylesheet" type="text/css" href="../css/styles.css">
    </head>
    <body>
        <h1>Admin Login</h1>
        <form method="post" action="index.php">
            Username: <input type="text" name="login" required><br>
            Password: <input type="password" name="password" required><br>
            <input type="submit" value="Log in">
        </form>
    </body>
    </html>
<?php
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_file'])) {
        $fileId = $_POST['file_id'];
        deleteFileInfo($fileId);
        echo 'File info deleted successfully.';
    } else if (isset($_POST['delete_scan'])) {
        $scanId = $_POST['scan_id'];
        deleteScanResult($scanId);
        echo 'Scan result deleted successfully.';
    } else if (isset($_POST['create_admin'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        createAdmin($username, $password);
        echo 'Admin created successfully.';
    } else if (isset($_POST['change_password'])) {
        $username = $_POST['username'];
        $newPassword = $_POST['new_password'];
        changeAdminPassword($username, $newPassword);
        echo 'Password changed successfully.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="index.php?logout=true">Log out</a>

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

    <h2>Create Admin</h2>
    <form action="index.php" method="post">
        <input type="hidden" name="create_admin" value="1">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Create Admin">
    </form>

    <h2>Change Admin Password</h2>
    <form action="index.php" method="post">
        <input type="hidden" name="change_password" value="1">
        Username: <input type="text" name="username"><br>
        New Password: <input type="password" name="new_password"><br>
        <input type="submit" value="Change Password">
    </form>
</body>
</html>
