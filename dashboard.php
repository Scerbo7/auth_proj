<?php
// Start the session to manage user login and session variables
session_start();

// Check if the user is logged in by verifying the presence of 'user_id' in the session
// If not, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit(); // Ensure the script stops after redirection
}

// Include the database connection file to access the database
include 'db_connect.php';

// Handle the form submission for adding a new schedule item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    // Get and sanitize the user input for event name and date
    $event_name = trim($_POST['event_name']); // Remove extra spaces
    $event_date = trim($_POST['event_date']); // Remove extra spaces

    // Ensure both fields are filled in
    if (!empty($event_name) && !empty($event_date)) {
        // Prepare an SQL statement to insert the event into the database
        $stmt = $conn->prepare("INSERT INTO schedules (user_id, event_name, event_date) VALUES (?, ?, ?)");
        // Bind the parameters to the query: user ID, event name, and event date
        $stmt->bind_param("iss", $_SESSION['user_id'], $event_name, $event_date);

        // Execute the query and check for success
        if ($stmt->execute()) {
            $success_message = "Event added successfully!"; // Success feedback
        } else {
            $error_message = "Failed to add event: " . $stmt->error; // Show error details
        }

        $stmt->close(); // Close the prepared statement to free resources
    } else {
        $error_message = "Both event name and date are required."; // Validation error feedback
    }
}

// Handle event deletion when the delete button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'])) {
    // Get the event ID to be deleted (ensure it's an integer)
    $event_id = intval($_POST['event_id']);
    // Prepare an SQL statement to delete the event from the database
    $stmt = $conn->prepare("DELETE FROM schedules WHERE id = ? AND user_id = ?");
    // Bind the event ID and user ID parameters to the query
    $stmt->bind_param("ii", $event_id, $_SESSION['user_id']);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $success_message = "Event removed successfully!"; // Success feedback
    } else {
        $error_message = "Failed to remove event: " . $stmt->error; // Show error details
    }

    $stmt->close(); // Close the prepared statement to free resources
}

// Fetch the user's schedule from the database to display on the dashboard
$schedule = []; // Initialize an empty array to hold the schedule
$stmt = $conn->prepare("SELECT id, event_name, event_date FROM schedules WHERE user_id = ? ORDER BY event_date ASC");
// Bind the user ID to the query
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result of the query

// Loop through each row in the result and add it to the schedule array
while ($row = $result->fetch_assoc()) {
    $schedule[] = $row; // Add the event data to the schedule
}
$stmt->close(); // Close the prepared statement to free resources
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Link Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link Google Fonts for custom typography -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Inline CSS for custom styling -->
    <style>
        /* General body styling */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        /* Navbar styling */
        nav {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-right: 15px;
            margin-left: 10px;
            font-size: 18px;
        }
        /* Container styling */
        .container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        /* Schedule list styling */
        .schedule-list {
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
        }
        /* Button styling */
        .btn-custom {
            background-color: #ffffff;
            color: #2575fc;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            font-weight: bold;
            margin: 5px;
        }
        .btn-custom:hover {
            background-color: #2575fc;
            color: white;
        }
        /* Table styling */
        table {
            width: 100%;
            color: white;
        }
        table th, table td {
            padding: 10px;
        }
        table th {
            text-align: left;
        }
    </style>
</head>
<body>
    <nav>
        <div style="font-weight: bold">Dashboard</div>
        <div>
            <button class="nav-button">
                <a href="index.php">Home</a>
            </button>
            <button class="nav-button">
                <a href="logout.php">Logout</a>
            </button>
        </div>
    </nav>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
        <p>Build and manage your schedule below:</p>

        <!-- Form to add a new event -->
        <form method="POST" class="w-100" style="max-width: 600px;">
            <div class="mb-3">
                <label for="event_name" class="form-label">Event Name</label>
                <input type="text" class="form-control" id="event_name" name="event_name" required>
            </div>
            <div class="mb-3">
                <label for="event_date" class="form-label">Event Date</label>
                <input type="date" class="form-control" id="event_date" name="event_date" required>
            </div>
            <button type="submit" name="add_event" class="btn btn-custom w-100">Add Event</button>
        </form>

        <!-- Display the schedule -->
        <div class="schedule-list">
            <h2>Your Schedule</h2>
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedule as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" name="delete_event" class="btn btn-custom">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

