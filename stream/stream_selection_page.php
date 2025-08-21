<?php
session_start();
include('../Database/database_connection.php');

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'student') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

$streams_query = "SELECT StreamID, StreamName FROM streams";
$streams_result = mysqli_query($conn, $streams_query);

$student_id = $_SESSION['UserID'];
$check_stream_query = "SELECT StreamChosen FROM users WHERE UserID = ?";
$stmt = mysqli_prepare($conn, $check_stream_query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $stream_chosen);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($stream_chosen) {
    header("Location: ../student/student_dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedStream'])) {
    $selected_stream = $_POST['selectedStream'];

    $insert_stream_query = "INSERT INTO student_streams (StudentID, StreamID) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $insert_stream_query);
    mysqli_stmt_bind_param($stmt, "ii", $student_id, $selected_stream);

    if (mysqli_stmt_execute($stmt)) {
        $update_stream_query = "UPDATE users SET StreamChosen = 1 WHERE UserID = ?";
        $update_stmt = mysqli_prepare($conn, $update_stream_query);
        mysqli_stmt_bind_param($update_stmt, "i", $student_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        header("Location: ../student/student_dashboard.php");
        exit();
    } else {
        $error_message = "Failed to choose stream. Please try again.";
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Stream</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #004a7c;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #004a7c; 
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #003366; 
        }
        .error {
            color: red;
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Select Your Stream</h1>

    <?php if (isset($error_message)): ?>
        <div class="error">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <label for="streams">Available Streams:</label>
        <select name="selectedStream" id="streams" required>
            <option value="">Select a stream</option>
            <?php while ($stream = mysqli_fetch_assoc($streams_result)): ?>
                <option value="<?php echo $stream['StreamID']; ?>"><?php echo htmlspecialchars($stream['StreamName']); ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Choose Stream</button>
    </form>
</body>
</html>
