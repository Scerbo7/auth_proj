<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        nav {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-right: 15px;
            transition: color 0.3s ease;
        }
        nav a:hover {
            color: #d4d4d4;
        }
        .dashboard-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .dashboard-container h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .dashboard-container p {
            font-size: 1.2rem;
        }
        .btn-custom {
            background-color: #ffffff;
            color: #2575fc;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: bold;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        .btn-custom:hover {
            background-color: #2575fc;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div>
            <a href="index.php">Auth Project Dashboard</a>
        </div>
        <div>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
        <p>This is your dashboard. Here, you can manage your account and view updates.</p>
        <a href="index.php" class="btn btn-custom">Back to Home</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>