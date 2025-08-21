<?php 
include '../Database/database_connection.php';

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

$query = "SELECT 
            students.StudentID, 
            students.FirstName, 
            students.LastName, 
            students.Grade AS StudentGrade, 
            results.Subject, 
            results.Grade AS ResultGrade, 
            results.Term, 
            results.Mark
          FROM 
            results
          JOIN 
            students ON results.StudentID = students.StudentID
          ORDER BY 
            results.Term ASC, students.LastName ASC";

$result = mysqli_query($conn, $query);

$query_for_statistics = "SELECT results.ResultID, students.FirstName, students.LastName, results.Subject, results.Grade, results.Term, results.Mark
                         FROM results
                         JOIN students ON results.StudentID = students.StudentID
                         ORDER BY results.Term ASC, students.LastName ASC";
$result_for_statistics = mysqli_query($conn, $query_for_statistics);

$grades = [];
$pass_counts = [];
$fail_counts = [];
$passing_mark = 40; 

if (mysqli_num_rows($result_for_statistics) > 0) {
    while ($row = mysqli_fetch_assoc($result_for_statistics)) {
        $grade = $row['Grade'];
        if (!isset($grades[$grade])) {
            $grades[$grade] = [];
            $pass_counts[$grade] = 0;
            $fail_counts[$grade] = 0;
        }
        $grades[$grade][] = $row['Mark'];
        if ($row['Mark'] >= $passing_mark) {
            $pass_counts[$grade]++;
        } else {
            $fail_counts[$grade]++;
        }
    }
}

$pass_percentage = [];
$pie_labels = [];
$pass_data = [];
$fail_data = [];

foreach ($grades as $grade => $marks) {
    $total_students = count($marks);
    if ($total_students > 0) {
        $pass_percentage[$grade] = ($pass_counts[$grade] / $total_students) * 100;
        $pie_labels[] = $grade;
        $pass_data[] = $pass_counts[$grade];
        $fail_data[] = $fail_counts[$grade];
    }
}

$line_labels = [];
$line_data = [];

$query_for_linear = "SELECT Term,Grade, AVG(Mark) as average_mark FROM results GROUP BY Term ORDER BY Term ASC";
$result_for_linear = mysqli_query($conn, $query_for_linear);

while ($row = mysqli_fetch_assoc($result_for_linear)) {
    $line_labels[] = "Term " . $row['Term'];
    $line_data[] = (float)$row['average_mark'];
}

$student_labels = [];
$student_marks = [];

mysqli_data_seek($result, 0); 
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $student_labels[] = "{$row['FirstName']} {$row['LastName']} - {$row['Subject']} (Term {$row['Term']}, Grade: {$row['ResultGrade']})"; // Include term and grade

        $student_marks[] = (float)$row['Mark'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Student Results</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        canvas {
            margin-top: 20px;
            max-width: 100%; 
        }
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two columns layout */
            gap: 20px; /* Space between the charts and table */
            margin-top: 20px;
        }
        .pie-chart, .table-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Results</h1>
        
        <div class="charts-container">
            <div class="pie-chart">
                <canvas id="passPercentageChart" width="200" height="100"></canvas>
                <h2>Pass Percentage Per Grade</h2>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Grade</th>
                            <th>Pass Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($pass_percentage as $grade => $percentage) {
                            echo "<tr>
                                    <td>{$grade}</td>
                                    <td>" . round($percentage, 2) . "%</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2>Average Marks Per Term</h2>
        <canvas id="resultGraph" width="400" height="200"></canvas>

        <h2>Individual Student Results</h2>
        <canvas id="studentResultsChart" width="400" height="200"></canvas>


    </div>

    <script>
        const pieLabels = <?php echo json_encode($pie_labels); ?>;
        const passData = <?php echo json_encode($pass_data); ?>;
        const failData = <?php echo json_encode($fail_data); ?>;

        const totalPassCounts = passData.reduce((a, b) => a + b, 0);
        const totalFailCounts = failData.reduce((a, b) => a + b, 0);

        const ctx1 = document.getElementById('passPercentageChart').getContext('2d');
        const passPercentageChart = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['Passed', 'Failed'],
                datasets: [{
                    label: 'Pass/Fail',
                    data: [totalPassCounts, totalFailCounts],
                    backgroundColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Overall Pass/Fail Percentage',
                    },
                }
            }
        });

        const lineLabels = <?php echo json_encode($line_labels); ?>;
        const lineData = <?php echo json_encode($line_data); ?>;

        const ctx2 = document.getElementById('resultGraph').getContext('2d');
        const resultGraph = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: lineLabels,
                datasets: [{
                    label: 'Average Marks',
                    data: lineData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Average Marks Per Term',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });

        const studentLabels = <?php echo json_encode($student_labels); ?>;
        const studentMarks = <?php echo json_encode($student_marks); ?>;

        const ctx3 = document.getElementById('studentResultsChart').getContext('2d');
        const studentResultsChart = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: studentLabels,
                datasets: [{
                    label: 'Marks',
                    data: studentMarks,
                    backgroundColor: 'rgba(54, 162, 235, 1)',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Individual Student Marks',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>
</body>
</html>
