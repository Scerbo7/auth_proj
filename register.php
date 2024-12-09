<?php
require 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password
        ]);
        echo "Registration successful! <a href='login.php'>Login</a>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<h2>Register</h2>
<form method="post">
    <label for="name">Name</label><br>
    <input type="text" name="name" id="name" required><br>

    <label for="email">Email</label><br>
    <input type="email" name="email" id="email" required><br>

    <label for="password">Password</label><br>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Register</button>
</form>

<p><a href="login.php">Already have an account? Login here</a></p>
