<?php
$servername = "sql112.infinityfree.com";   // Default for XAMPP
$username = "if0_37913243";          // Default for XAMPP
$password = "vPtaQzgz8f0P";              // Default is empty
$dbname = "if0_37913243_389_database";    // Ensure this matches your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
