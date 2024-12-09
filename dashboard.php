<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
<p>This is your dashboard.</p>

<p><a href="logout.php">Logout</a></p>
