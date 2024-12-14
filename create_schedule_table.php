<?php
// Include the database connection file to establish a connection to the database
include 'db_connect.php';

// SQL statement to create a new table named 'schedules'
// The table will store information about user schedules/events
$sql = "CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- Unique ID for each schedule entry, auto-incremented
    user_id INT NOT NULL,                      -- References the user who created the event, cannot be null
    event_name VARCHAR(255) NOT NULL,          -- Name of the event, up to 255 characters long, cannot be null
    event_date DATE NOT NULL,                  -- The date of the event, in YYYY-MM-DD format, cannot be null
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    -- Defines a foreign key relationship:
    -- user_id links to the 'id' column in the 'users' table
    -- If a user is deleted, all their events in 'schedules' will also be deleted
)";

// Execute the SQL query and check if the table was created successfully
if ($conn->query($sql) === TRUE) {
    // If the query is successful, output a success message
    echo "Table 'schedules' created successfully!";
} else {
    // If there's an error, output the error message for debugging
    echo "Error creating table: " . $conn->error;
}

// Close the database connection to free up resources
$conn->close();
?>
