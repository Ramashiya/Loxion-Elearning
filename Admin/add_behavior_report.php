<?php
include('../Database/database_connection.php'); 

if (!isset($_SESSION['UserID']) || !isset($_SESSION['role'])) {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

$user_id = $_SESSION['UserID'];
$user_role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $report_date = $_POST['report_date'];
    $description = $_POST['description'];
    $reported_by = $_POST['reported_by'];

    if (empty($student_id) || empty($report_date) || empty($description)) {
        die('Please fill in all fields.');
    }

    $sql = "INSERT INTO behavior_reports (StudentID, ReportDate, Description, ReportedBy) 
            VALUES ('$student_id', '$report_date', '$description', '$reported_by')";

    if (mysqli_query($conn, $sql)) {
        header('Location: behavior.php');
        exit();
    } else {
        die('Error: ' . mysqli_error($conn)); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Behavior Report</title>
    <link rel="stylesheet" href="styless.css"> 
</head>
<body>
    <div class="container">
        <h2>Add Behavior Report</h2>
        <form action="add_behavior_report.php" method="POST">
            <label for="student_id">Student:</label>
            <select name="student_id" required>
                <?php
                $students = mysqli_query($conn, "SELECT StudentID, CONCAT(FirstName, ' ', LastName) AS Name FROM students");
                while ($student = mysqli_fetch_assoc($students)) {
                    echo "<option value='{$student['StudentID']}'>{$student['Name']}</option>";
                }
                ?>
            </select><br>

            <label for="report_date">Report Date:</label>
            <input type="date" name="report_date" required><br>

            <label for="description">Description:</label>
            <textarea name="description" required></textarea><br>

            <input type="hidden" name="reported_by" value="<?php echo $user_id; ?>">
            <button type="submit">Add Report</button>
        </form>
    </div>
</body>
</html>
