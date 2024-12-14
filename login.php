<?php
// Include the database connection file to access the database
include 'db_connect.php';
// Start the session to manage user login and session variables
session_start();

// Check if the request method is POST (i.e., form submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and trim the email and password input fields to remove extra spaces
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate that both email and password fields are not empty
    if (empty($email) || empty($password)) {
        die("Both fields are required."); // Stop execution if validation fails
    }

    // Prepare an SQL query to retrieve the user details based on the email
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // Bind the email parameter to the query
    $stmt->execute();
    $stmt->store_result(); // Store the query result for further processing

    // Check if a user with the provided email exists
    if ($stmt->num_rows > 0) {
        // Bind the result columns to variables
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch(); // Fetch the result from the query

        // Verify the entered password against the hashed password in the database
        if (password_verify($password, $hashed_password)) {
            // Store user ID and username in session variables for logged-in state
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $username;
            header("Location: dashboard.php"); // Redirect to the dashboard page
            exit(); // Stop further script execution after redirection
        } else {
            // Output an error message if the password doesn't match
            echo "Incorrect email or password.";
        }
    } else {
        // Output an error message if no user exists with the provided email
        echo "No account found with that email.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .form-container {
            max-width: 400px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .btn-custom {
            background-color: #ffffff;
            color: #2575fc;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #2575fc;
            color: white;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-custom w-100">Login</button>
            <div class="mt-3">
                <small>Don't have an account? <a href="register.php" style="color: #ffffff; text-decoration: underline;">Register</a></small>
            </div>
        </form>
    </div>
</body>
</html>
