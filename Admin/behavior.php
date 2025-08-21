<?php
include('../Database/database_connection.php');

if (!isset($_SESSION['UserID']) || !isset($_SESSION['role'])) {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

$user_id = $_SESSION['UserID'];
$user_role = $_SESSION['role'];


$errors = [];
$success_message = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_report'])) {
    $report_id = (int)$_POST['report_id']; 
    $delete_sql = "DELETE FROM behavior_reports WHERE ReportID = '$report_id'";


    if (mysqli_query($conn, $delete_sql)) {
        $success_message = 'Report deleted successfully!';
    } else {
        $errors[] = 'Error deleting report: ' . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_report'])) {
    $report_id = $_POST['report_id'];
    $student_id = $_POST['student_id'];
    $report_date = $_POST['report_date'];
    $description = $_POST['description'];

    if (empty($student_id) || empty($report_date) || empty($description)) {
        $errors[] = 'Please fill in all fields.';
    } else {
        $sql = "UPDATE behavior_reports SET StudentID='$student_id', ReportDate='$report_date', Description='$description' WHERE ReportID='$report_id'";
        if (mysqli_query($conn, $sql)) {
            $success_message = 'Report updated successfully!';
        } else {
            $errors[] = 'Error updating report: ' . mysqli_error($conn);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_report'])) {
    $student_id = $_POST['student_id'];
    $report_date = $_POST['report_date'];
    $description = $_POST['description'];

    if (empty($student_id) || empty($report_date) || empty($description)) {
        $errors[] = 'Please fill in all fields.';
    } else {
        $sql = "INSERT INTO behavior_reports (StudentID, ReportDate, Description, ReportedBy) 
                VALUES ('$student_id', '$report_date', '$description', '$user_id')";
        if (mysqli_query($conn, $sql)) {
            $success_message = 'Report added successfully!';
        } else {
            $errors[] = 'Error adding report: ' . mysqli_error($conn);
        }
    }
}

$students_sql = "SELECT StudentID, CONCAT(FirstName, ' ', LastName) AS StudentName FROM students";
$students_result = mysqli_query($conn, $students_sql);

if (!$students_result) {
    die('Error fetching students: ' . mysqli_error($conn));
}

if ($user_role == 'admin') {
    $sql = "SELECT br.ReportID, CONCAT(s.FirstName, ' ', s.LastName) AS StudentName, br.ReportDate, br.Description, u.Name AS ReportedByName, s.StudentID
            FROM behavior_reports br
            JOIN students s ON br.StudentID = s.StudentID
            JOIN users u ON br.ReportedBy = u.UserID";
} elseif ($user_role == 'teacher') {
    $sql = "SELECT br.ReportID, CONCAT(s.FirstName, ' ', s.LastName) AS StudentName, br.ReportDate, br.Description, u.Name AS ReportedByName, s.StudentID
            FROM behavior_reports br
            JOIN students s ON br.StudentID = s.StudentID
            JOIN users u ON br.ReportedBy = u.UserID
            WHERE br.ReportedBy = '$user_id' OR s.TeacherID = '$user_id'";
} elseif ($user_role == 'parent') {
    $sql = "SELECT br.ReportID, CONCAT(s.FirstName, ' ', s.LastName) AS StudentName, br.ReportDate, br.Description, u.Name AS ReportedByName, s.StudentID
            FROM behavior_reports br
            JOIN students s ON br.StudentID = s.StudentID
            JOIN users u ON br.ReportedBy = u.UserID
            WHERE s.ParentID = '$user_id'";
} else {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query Failed: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Behavior Reports</title>
    <style>
  

       

        .h2 {
            text-align: center;
            color: #4a4a4a;
            margin-bottom: 20px;
        }

        /* Table styles */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .report-table th, .report-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid black;
        }

        /* Button styles */
        button, .add-btn, .edit-btn, .delete-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-btn {
            background-color: #4CAF50;
            color: white;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        button:hover, .edit-btn:hover, .delete-btn:hover {
            opacity: 0.8;
        }

        /* Form styles */
        form {
            display: flex;
            flex-direction: column;
        }

        form label, form select, form input, form textarea {
            margin-bottom: 10px;
        }

        /* Responsive design */
        @media screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }

            form {
                flex-direction: column;
            }

            .report-table, .report-table th, .report-table td {
                font-size: 14px;
                padding: 8px;
            }

            button, .add-btn, .edit-btn, .delete-btn {
                font-size: 14px;
                padding: 8px 12px;
            }
        }

        @media screen and (max-width: 480px) {
            .h2 {
                font-size: 20px;
            }

            .report-table th, .report-table td {
                display: block;
                width: 100%;
            }

            .report-table tr {
                margin-bottom: 10px;
                display: block;
                border: 1px solid #ddd;
            }

            .report-table th {
                background-color: #f4f4f4;
            }

            form {
                font-size: 14px;
            }

            button, .add-btn, .edit-btn, .delete-btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }

    </style>
    <script>
        window.onload = function() {
            let today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('input[type="date"]').forEach(function(dateInput) {
                dateInput.setAttribute('min', today);
                dateInput.setAttribute('max', today);
            });
        };

        setTimeout(function() {
            var successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 10000);

        function editReport(reportId, studentId, reportDate, description) {
            document.getElementById('report_id').value = reportId;
            document.getElementById('student_id').value = studentId;
            document.getElementById('report_date').value = reportDate;
            document.getElementById('description').value = description;
            document.getElementById('editReportForm').style.display = 'block';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2 class="h2">Behavior Reports</h2>

        <?php if (!empty($errors)): ?>
            <p style="color: red;"><?php echo implode('<br>', $errors); ?></p>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <p id="successMessage" style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if ($user_role == 'admin'): ?>
            <h3>Add New Report</h3>
            <form method="post">
                <label for="student_id">Student:</label>
                <select name="student_id" required>
                    <option value="">Select Student</option>
                    <?php while ($student = mysqli_fetch_assoc($students_result)) { ?>
                        <option value="<?php echo $student['StudentID']; ?>"><?php echo $student['StudentName']; ?></option>
                    <?php } ?>
                </select>
                <label for="report_date">Report Date:</label>
                <input type="date" name="report_date" required>
                <label for="description">Description:</label>
                <textarea name="description" required></textarea>
                <button type="submit" name="add_report" class="add-btn">Add Report</button>
            </form>
        <?php endif; ?>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Student Name</th>
                    <th>Report Date</th>
                    <th>Description</th>
                    <th>Reported By</th>
                    <?php if ($user_role == 'teacher' || $user_role == 'admin'): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['ReportID']; ?></td>
                        <td><?php echo $row['StudentName']; ?></td>
                        <td><?php echo $row['ReportDate']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><?php echo $row['ReportedByName']; ?></td>
                        <?php if ($user_role == 'teacher' || $user_role == 'admin'): ?>
                            <td>
                                <button class="edit-btn" onclick="editReport('<?php echo $row['ReportID']; ?>', '<?php echo $row['StudentID']; ?>', '<?php echo $row['ReportDate']; ?>', '<?php echo addslashes($row['Description']); ?>')">Edit</button>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="report_id" value="<?php echo $row['ReportID']; ?>">
                                    <button type="submit" name="delete_report" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div id="editReportForm" style="display: none;">
            <h3>Edit Report</h3>
            <form method="post">
                <input type="hidden" name="report_id" id="report_id">
                <label for="student_id">Student:</label>
                <select name="student_id" id="student_id" required>
                    <?php
                    mysqli_data_seek($students_result, 0); 
                    while ($student = mysqli_fetch_assoc($students_result)) { ?>
                        <option value="<?php echo $student['StudentID']; ?>"><?php echo $student['StudentName']; ?></option>
                    <?php } ?>
                </select>
                <label for="report_date">Report Date:</label>
                <input type="date" name="report_date" id="report_date" required>
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
                <button type="submit" name="edit_report" class="edit-btn">Update Report</button>
            </form>
        </div>
    </div>
</body>
</html>

