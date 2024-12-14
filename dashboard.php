<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

// Handle form submission to add a new schedule item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $event_name = trim($_POST['event_name']);
    $event_date = trim($_POST['event_date']);

    if (!empty($event_name) && !empty($event_date)) {
        $stmt = $conn->prepare("INSERT INTO schedules (user_id, event_name, event_date) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $_SESSION['user_id'], $event_name, $event_date);

        if ($stmt->execute()) {
            $success_message = "Event added successfully!";
        } else {
            $error_message = "Failed to add event: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Both event name and date are required.";
    }
}

// Handle event deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'])) {
    $event_id = intval($_POST['event_id']);
    $stmt = $conn->prepare("DELETE FROM schedules WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $success_message = "Event removed successfully!";
    } else {
        $error_message = "Failed to remove event: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch the user's schedule
$schedule = [];
$stmt = $conn->prepare("SELECT id, event_name, event_date FROM schedules WHERE user_id = ? ORDER BY event_date ASC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $schedule[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        nav {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-right: 15px;
        }
        .container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .schedule-list {
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
        }
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
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
        <p>Build and manage your schedule below:</p>

        <!-- Add Event Form -->
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

        <!-- Display Schedule -->
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
