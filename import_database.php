<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Change this if your MySQL user is different
$password = ""; // Change this if your MySQL password is not empty

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Create the database
$database_name = '389_database';
$sql = "CREATE DATABASE IF NOT EXISTS $database_name";
if ($conn->query($sql) === TRUE) {
    echo "Database '$database_name' created successfully.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Step 2: Select the database
$conn->select_db($database_name);

// Step 3: Create `users` table
$create_users_table = "
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";
if ($conn->query($create_users_table) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    die("Error creating 'users' table: " . $conn->error);
}

// Step 4: Create `schedules` table
$create_schedules_table = "
CREATE TABLE IF NOT EXISTS `schedules` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `event_name` VARCHAR(150) NOT NULL,
  `event_date` DATE NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";
if ($conn->query($create_schedules_table) === TRUE) {
    echo "Table 'schedules' created successfully.<br>";
} else {
    die("Error creating 'schedules' table: " . $conn->error);
}

// Step 5: Create `posts` table
$create_posts_table = "
CREATE TABLE IF NOT EXISTS `posts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `content` TEXT NOT NULL,
  `user_id` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";
if ($conn->query($create_posts_table) === TRUE) {
    echo "Table 'posts' created successfully.<br>";
} else {
    die("Error creating 'posts' table: " . $conn->error);
}

// Close connection
$conn->close();
echo "All tables have been successfully created in '$database_name'.";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Import</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 1.1rem;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Import Complete</h1>
        <p>All the necessary tables have been successfully created in the <strong>389_database</strong> database.</p>

        <a href="javascript:history.back()" class="btn">Go Back</a>
    </div>
</body>
</html>