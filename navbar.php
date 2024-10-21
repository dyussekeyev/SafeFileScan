<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

// Check if the user is logging in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login']) && isset($_POST['password'])) {
    $username = $_POST['login'];
    $password = $_POST['password'];

    // Validate user credentials (you need to implement the actual validation logic)
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
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

<div class="navbar">
    <a href="index.php">Home</a>
    <?php if (isset($_SESSION['username'])): ?>
        <a href="#">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
        <a href="index.php?logout=true">Log out</a>
    <?php else: ?>
        <form method="post" action="index.php" style="float: left;">
            <input type="text" name="login" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Log in">
        </form>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
    <?php endif; ?>
</div>
