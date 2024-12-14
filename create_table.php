<?php
// Enable error reporting to help debug any issues
// 'display_errors' makes PHP show errors directly on the webpage
ini_set('display_errors', 1);
// 'display_startup_errors' shows errors that occur during the PHP startup sequence
ini_set('display_startup_errors', 1);
// 'error_reporting(E_ALL)' ensures that all types of errors and warnings are displayed
error_reporting(E_ALL);

// Include the database connection file
// This file is expected to establish a connection to the database using $conn
include 'db_connect.php';

// SQL query to create the 'users' table if it doesn't already exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,     -- Unique ID for each user, automatically incremented
    username VARCHAR(50) NOT NULL,            -- Stores the username, max length 50 characters, cannot be null
    email VARCHAR(100) UNIQUE NOT NULL,       -- Stores the user's email, must be unique, cannot be null
    password VARCHAR(255) NOT NULL,           -- Stores the hashed password, max length 255 characters, cannot be null
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    -- Automatically records the date and time when the row is created
)";

// Use the database connection to execute the SQL query
if ($conn->query($sql) === TRUE) {
    // If the query is successful, let the user know the table was created
    // or that it already exists (because of the IF NOT EXISTS condition)
    echo "Table 'users' created successfully or already exists.";
} else {
    // If the query fails, output the error message for debugging purposes
    echo "Error creating table: " . $conn->error;
}

// Notes:
// - It's important to sanitize inputs when working with user data (not shown here).
// - Use hashed passwords (e.g., bcrypt) to ensure security when storing user credentials.

// Close the database connection to free up resources
$conn->close();
?>

