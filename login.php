<?php
require 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                echo "Login successful! Welcome, " . htmlspecialchars($user['name']);
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with this email.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<h2>Login</h2>
<form method="post">
    <label for="email">Email</label><br>
    <input type="email" name="email" id="email" required><br>

    <label for="password">Password</label><br>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Login</button>
</form>

<p><a href="register.php">Don't have an account? Register here</a></p>
