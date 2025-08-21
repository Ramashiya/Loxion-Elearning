<?php
include('../Database/database_connection.php');

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

$errorMessage = '';
$successMessage = '';

$currentDate = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $location = $_POST['location'];
    $createdBy = $_SESSION['UserID'];

    if ($startDate < $currentDate) {
        $errorMessage = "Start date cannot be in the past.";
    } elseif ($endDate < $startDate) {
        $errorMessage = "End date must be after the start date.";
    } else {
        $sql = "INSERT INTO events (Title, Description, StartDate, EndDate, Location, CreatedBy) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $title, $description, $startDate, $endDate, $location, $createdBy);
        if ($stmt->execute()) {
            $successMessage = "Event added successfully.";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #e9ecef;
    border-bottom: 1px solid #dee2e6;
}

        .h1 {
            margin: 0;
            font-size: 24px;
        }

        .event-form {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .event-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .event-form input[type="text"],
        .event-form input[type="date"],
        .event-form input[type="datetime-local"],
        .event-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .event-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .event-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        .success {
            color: green;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <header>
        <h1 class="h1">Add New Event</h1>
    </header>
    <main>
        <div class="event-form">
            <?php if ($errorMessage): ?>
                <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <form action="" method="post">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" min="<?php echo $currentDate; ?>" required>

                <label for="end_date">End Date:</label>
                <input type="datetime-local" id="end_date" name="end_date" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <input type="submit" value="Add Event">
            </form>
        </div>
    </main>
</body>
</html>
