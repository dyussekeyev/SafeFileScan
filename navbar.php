<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

// Check if the user is logging in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login']) && isset($_POST['password'])) {
    $username = $_POST['login'];
    $password = $_POST['password'];

    // Validate user credentials (you need to implement the actual validation logic)
    $stmt = $conn->prepare("SELECT username, role FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($username, $role);
    $stmt->fetch();
    
    if ($username) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
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
    <a href="index.php" style="float: left;">Home</a>
    
    <!-- Centered search form -->
    <form method="get" action="search.php" style="text-align: center; margin: 0 auto;">
        <input type="text" name="hash" placeholder="Search by hash" required>
        <input type="submit" value="Search">
    </form>

    <?php if (isset($_SESSION['username'])): ?>
        <div style="float: right;">
            <a href="#">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <a href="index.php?logout=true">Log out</a>
        </div>
    <?php else: ?>
        <form method="post" action="index.php" style="float: right;">
            <input type="text" name="login" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Log in">
        </form>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
    <?php endif; ?>
</div>
