<?php 
include '../Database/database_connection.php';

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

$attendance_records = [];

$query = "
    SELECT ac.ConfirmationID, ac.RegisterID, s.FirstName, s.LastName, ac.ConfirmedStatus, ac.Latitude, ac.Longitude 
    FROM attendance_confirmation ac
    JOIN students s ON ac.StudentID = s.StudentID
";

$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $attendance_records[] = $row;
    }
} else {
    echo "<script>alert('Error fetching records. Please try again later.');</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
     
        h1 {
            text-align: center;
            padding: 7px;
            color: black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            table {
                width: 100%;
                overflow-x: auto;
                display: block;
                white-space: nowrap;
            }

            th, td {
                padding: 8px;
                font-size: 14px;
            }

            h1 {
                font-size: 24px;
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            th, td {
                padding: 6px;
                font-size: 12px;
            }

            h1 {
                font-size: 20px;
                padding: 10px;
            }

            /* Stack table rows into blocks */
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 10px;
                border-bottom: 1px solid #ddd;
            }

            td {
                display: flex;
                justify-content: space-between;
                padding: 10px;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                width: 50%;
                color: #4CAF50;
            }
        }
    </style>
</head>
<body>

<h1>Attendance Records</h1>

<table>
    <thead>
        <tr>
            <th>Confirmation ID</th>
            <th>Register ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Attendance Status</th>
            <th>Latitude</th>
            <th>Longitude</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($attendance_records)): ?>
        <?php foreach ($attendance_records as $record): ?>
            <tr>
                <td data-label="Confirmation ID"><?php echo htmlspecialchars($record['ConfirmationID']); ?></td>
                <td data-label="Register ID"><?php echo htmlspecialchars($record['RegisterID']); ?></td>
                <td data-label="First Name"><?php echo htmlspecialchars($record['FirstName']); ?></td>
                <td data-label="Last Name"><?php echo htmlspecialchars($record['LastName']); ?></td>
                <td data-label="Attendance Status"><?php echo htmlspecialchars($record['ConfirmedStatus']); ?></td>
                <td data-label="Latitude">
                    <a href="https://www.google.com/maps?q=<?php echo htmlspecialchars($record['Latitude']); ?>,<?php echo htmlspecialchars($record['Longitude']); ?>" target="_blank">
                        <?php echo htmlspecialchars($record['Latitude']); ?>
                    </a>
                </td>
                <td data-label="Longitude">
                    <a href="https://www.google.com/maps?q=<?php echo htmlspecialchars($record['Latitude']); ?>,<?php echo htmlspecialchars($record['Longitude']); ?>" target="_blank">
                        <?php echo htmlspecialchars($record['Longitude']); ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No attendance records found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
