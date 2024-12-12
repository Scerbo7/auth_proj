<?php
$servername = "localhost";   // Default for XAMPP
$username = "root";          // Default for XAMPP
$password = "";              // Default is empty
$dbname = "389_database";    // Ensure this matches your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
