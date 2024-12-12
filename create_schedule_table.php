<?php
include 'db_connect.php';

$sql = "CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'schedules' created successfully!";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>