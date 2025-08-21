<?php
include('../Database/database_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../Login_Logout/logout.php');
        exit();
    }

    if ($_POST['action'] == 'delete' && isset($_POST['assignment_id'])) {
        $assignmentID = intval($_POST['assignment_id']);

        // Delete related submissions
        $deleteSubmissionsSql = "DELETE FROM submissions WHERE AssignmentID = ?";
        $stmt = mysqli_prepare($conn, $deleteSubmissionsSql);
        mysqli_stmt_bind_param($stmt, 'i', $assignmentID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Delete the assignment
        $deleteSql = "DELETE FROM assignments WHERE AssignmentID = ?";
        $stmt = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($stmt, 'i', $assignmentID);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = "Assignment deleted successfully.";
        } else {
            $message = "Error deleting assignment: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }

    if ($_POST['action'] == 'add') {
        $subjectID = $_POST['subject'];
        $startDate = $_POST['start_date'];
        $dueDate = $_POST['due_date'];
        $grade = $_POST['grade'];
        $streamID = null;
        $subjectStreamID = null;

        if (in_array($grade, ['10', '11', '12'])) {
            $streamID = $_POST['stream'];
            $subjectStreamID = $_POST['subject'];
        }

        $fileTmpPath = $_FILES['assignment_file']['tmp_name'];
        $fileName = $_FILES['assignment_file']['name'];
        $uploadDir = __DIR__ . '/uploads/';
        $filePath = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Check for existing assignment with the same subject and due date
        $checkSql = ($grade == '8' || $grade == '9') 
            ? "SELECT * FROM assignments WHERE SubjectID = ? AND DueDate = ?"
            : "SELECT * FROM assignments WHERE StreamID = ? AND SubjectStreamID = ? AND DueDate = ?";

        $stmt = mysqli_prepare($conn, $checkSql);
        if ($grade == '8' || $grade == '9') {
            mysqli_stmt_bind_param($stmt, 'is', $subjectID, $dueDate);
        } else {
            mysqli_stmt_bind_param($stmt, 'iis', $streamID, $subjectStreamID, $dueDate);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $message = "An assignment already exists for this subject and due date.";
        } else {
            if (move_uploaded_file($fileTmpPath, $filePath)) {
                $sql = ($grade == '8' || $grade == '9') 
                    ? "INSERT INTO assignments (SubjectID, StartDate, DueDate, FilePath, Status, Grade) VALUES (?, ?, ?, ?, 'active', ?)"
                    : "INSERT INTO assignments (StreamID, SubjectStreamID, StartDate, DueDate, FilePath, Status, Grade) VALUES (?, ?, ?, ?, ?, 'active', ?)";

                $stmt = mysqli_prepare($conn, $sql);
                if ($grade == '8' || $grade == '9') {
                    mysqli_stmt_bind_param($stmt, 'isssi', $subjectID, $startDate, $dueDate, $filePath, $grade);
                } else {
                    mysqli_stmt_bind_param($stmt, 'iisssi', $streamID, $subjectStreamID, $startDate, $dueDate, $filePath, $grade);
                }

                if (mysqli_stmt_execute($stmt)) {
                    $message = "Assignment uploaded successfully.";
                } else {
                    $message = "Error: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            } else {
                $message = "No file uploaded or an error occurred during the upload.";
            }
        }
    }
}

// Fetch subjects, streams, and assignments
$subjects = [];
$result = $conn->query("SELECT SubjectID, SubjectName FROM subjects");
while ($row = $result->fetch_assoc()) {
    $subjects[$row['SubjectID']] = $row['SubjectName'];
}

$streams = [];
$result = $conn->query("SELECT StreamID, StreamName FROM streams");
while ($row = $result->fetch_assoc()) {
    $streams[$row['StreamID']] = $row['StreamName'];
}
$subject_streams = [];
$result = $conn->query("SELECT ss.StreamID, ss.SubjectID, ss.SubjectName 
    FROM subject_streams ss 
    JOIN streams s ON ss.StreamID = s.StreamID");

while ($row = $result->fetch_assoc()) {
    $subject_streams[$row['StreamID']]['SubjectIDs'][] = $row['SubjectID'];
    $subject_streams[$row['StreamID']]['SubjectNames'][] = $row['SubjectName'];
}

$assignments = [];
$sql = "SELECT a.AssignmentID, a.StartDate, a.DueDate, a.FilePath, a.Status, s.SubjectName
        FROM assignments AS a
        JOIN subjects AS s ON a.SubjectID = s.SubjectID
        WHERE a.Grade IN ('8', '9') OR (a.Grade IN ('10', '11', '12') AND a.StreamID IS NOT NULL)";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assignments</title>
</head>
<style>
.assignment-management h1 {
    font-size: 2.5em;
    color: #2c3e50;
    text-align: center;
    margin-bottom: 20px;
}

.assignment-management #assignmentForm {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #bdc3c7;
    border-radius: 5px;
    background-color: #ecf0f1;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.assignment-management label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #34495e;
}

.assignment-management input[type="date"],
.assignment-management input[type="file"],
.assignment-management select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #bdc3c7;
    border-radius: 5px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.assignment-management button[type="submit"] {
    background-color: #3498db;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
}

.assignment-management button[type="submit"]:hover {
    background-color: #2980b9;
}

.assignment-management h2 {
    font-size: 2em;
    color: #2c3e50;
    margin-top: 40px;
}

.assignment-management ul {
    list-style-type: none;
    padding: 0;
}

.assignment-management li {
    background-color: #fff;
    border: 1px solid #bdc3c7;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.assignment-management button[type="submit"] {
    background-color: #e74c3c;
    margin-left: 10px;
}

.assignment-management button[type="submit"]:hover {
    background-color: #c0392b;
}
</style>
<body>

<?php if (isset($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<div class="assignment-management">
<h1>Manage Assignments</h1>


<form id="assignmentForm" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    <label for="grade">Select Grade:</label>
    <select id="grade" name="grade" required>
        <option value="" disabled selected>--Select Grade--</option>
        <option value="8">Grade 8</option>
        <option value="9">Grade 9</option>
        <option value="10">Grade 10</option>
        <option value="11">Grade 11</option>
        <option value="12">Grade 12</option>
    </select>

    <label for="stream">Select Stream:</label>
    <select id="stream" name="stream" disabled>
        <option value="" disabled selected>--Select Stream--</option>
        <?php foreach ($streams as $streamID => $streamName): ?>
            <option value="<?php echo $streamID; ?>"><?php echo htmlspecialchars($streamName); ?></option>
        <?php endforeach; ?>
    </select>

    <label for="subject">Select Subject:</label>
    <select id="subject" name="subject" required>
        <option value="" disabled selected>--Select Subject--</option>
        <?php foreach ($subjects as $subjectID => $subjectName): ?>
            <option value="<?php echo $subjectID; ?>"><?php echo htmlspecialchars($subjectName); ?></option>
        <?php endforeach; ?>
    </select>

    <?php
// Get the current date in YYYY-MM-DD format
$currentDate = date('Y-m-d');
?>

<!-- In the HTML form -->
<label for="start_date">Start Date:</label>
<input type="date" id="start_date" name="start_date" min="<?php echo $currentDate; ?>" required>

<label for="due_date">Due Date:</label>
<input type="date" id="due_date" name="due_date" min="<?php echo $currentDate; ?>" required>


    <label for="assignment_file">Upload Assignment (PDF only):</label>
    <input type="file" id="assignment_file" name="assignment_file" accept="application/pdf" required>

    <button type="submit">Add Assignment</button>
</form>

<h2>Existing Assignments</h2>
<ul>
    <?php foreach ($assignments as $assignment): ?>
        <li>
            <strong><?php echo htmlspecialchars($assignment['SubjectName']); ?></strong><br>
            Start Date: <?php echo htmlspecialchars($assignment['StartDate']); ?><br>
            Due Date: <?php echo htmlspecialchars($assignment['DueDate']); ?><br>
            File: <a href="<?php echo htmlspecialchars($assignment['FilePath']); ?>" target="_blank">Download</a>
            <form method="post" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="assignment_id" value="<?php echo $assignment['AssignmentID']; ?>">
                <button type="submit">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeSelect = document.getElementById('grade');
    const streamSelect = document.getElementById('stream');
    const subjectSelect = document.getElementById('subject');

    const subjects = <?php echo json_encode($subjects); ?>;
    const subjectStreams = <?php echo json_encode($subject_streams); ?>;

    gradeSelect.addEventListener('change', function() {
        const grade = gradeSelect.value;
        subjectSelect.innerHTML = '<option value="" disabled selected>--Select Subject--</option>';
        streamSelect.disabled = true; 
        
        if (grade === '10' || grade === '11' || grade === '12') {
            streamSelect.disabled = false; 
            
            streamSelect.addEventListener('change', function() {
                const streamID = streamSelect.value;
                subjectSelect.innerHTML = '<option value="" disabled selected>--Select Subject--</option>';

                if (subjectStreams[streamID]) {
                    subjectStreams[streamID].SubjectIDs.forEach((subjectID, index) => {
                        subjectSelect.innerHTML += `<option value="${subjectID}">${subjectStreams[streamID].SubjectNames[index]}</option>`;
                    });
                }
            });
        } else {
            subjectSelect.innerHTML = '<option value="" disabled selected>--Select Subject--</option>';
            Object.keys(subjects).forEach((subjectID) => {
                subjectSelect.innerHTML += `<option value="${subjectID}">${subjects[subjectID]}</option>`;
            });
        }
    });

    gradeSelect.dispatchEvent(new Event('change'));
});
</script>
</body>
</html>
