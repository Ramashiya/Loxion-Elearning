<?php  
include '../Database/database_connection.php';  

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {     
    header('Location: ../Login_Logout/logout.php');     
    exit(); 
}

$errors = []; 
$message = "";  


if (isset($_POST['addStream'])) {     
    $streamName = mysqli_real_escape_string($conn, $_POST['streamName']);     
    $associatedSubjects = mysqli_real_escape_string($conn, $_POST['associatedSubjects']);     

    if (empty($streamName)) {         
        $errors[] = "Stream Name cannot be empty.";     
    } elseif (empty($associatedSubjects)) {         
        $errors[] = "Associated Subjects cannot be empty.";     
    } else {         
        $query = "INSERT INTO streams (StreamName) VALUES ('$streamName')";         
        if (mysqli_query($conn, $query)) {             
            $streamID = mysqli_insert_id($conn);             
            $subjectsArray = explode(',', $associatedSubjects);             
            foreach ($subjectsArray as $subjectName) {                 
                $subjectName = trim($subjectName);                 
                $subjectStreamQuery = "INSERT INTO subject_streams (StreamID, SubjectID, SubjectName) VALUES ('$streamID', NULL, '$subjectName')";                 
                if (!mysqli_query($conn, $subjectStreamQuery)) {                     
                    $errors[] = "Failed to add subject to stream: " . mysqli_error($conn);                 
                }             
            }             
            $message = "Stream and associated subjects added successfully.";          
        } else {             
            $errors[] = "Failed to add stream: " . mysqli_error($conn);         
        }     
    } 
}  


if (isset($_POST['editStream'])) {     
    $streamID = mysqli_real_escape_string($conn, $_POST['streamID']);     
    $newStreamName = mysqli_real_escape_string($conn, $_POST['newStreamName']);     

    if (empty($newStreamName)) {         
        $errors[] = "New Stream Name cannot be empty.";     
    } else {         
        $query = "UPDATE streams SET StreamName='$newStreamName' WHERE StreamID='$streamID'";         
        if (!mysqli_query($conn, $query)) {             
            $errors[] = "Failed to update stream: " . mysqli_error($conn);         
        } else {             
            $message = "Stream updated successfully.";          
        }     
    } 
}

if (isset($_POST['deleteStream'])) {     
    $streamID = mysqli_real_escape_string($conn, $_POST['streamID']);     

    mysqli_begin_transaction($conn);     
    try {         
        $deleteSubjectStreamQuery = "DELETE FROM subject_streams WHERE StreamID='$streamID'";         
        if (!mysqli_query($conn, $deleteSubjectStreamQuery)) {             
            throw new Exception("Error deleting from subject_streams: " . mysqli_error($conn));         
        }         

        $query = "DELETE FROM streams WHERE StreamID='$streamID'";         
        if (!mysqli_query($conn, $query)) {             
            throw new Exception("Error deleting from streams: " . mysqli_error($conn));         
        }         

        mysqli_commit($conn);         
        $message = "Stream and associated subjects deleted successfully.";      
    } catch (Exception $e) {         
        mysqli_rollback($conn);         
        $errors[] = $e->getMessage();     
    } 
}

$query = "SELECT s.StreamID, s.StreamName, GROUP_CONCAT(ss.SubjectName SEPARATOR ', ') AS Subjects
          FROM streams s
          LEFT JOIN subject_streams ss ON s.StreamID = ss.StreamID
          GROUP BY s.StreamID";
$streams_result = mysqli_query($conn, $query); 
if (!$streams_result) {     
    die("Query failed: " . mysqli_error($conn)); 
}

if (isset($_POST['addSubject'])) {     
    $subjectName = mysqli_real_escape_string($conn, $_POST['subjectName']);     

    if (empty($subjectName)) {         
        $errors[] = "Subject Name cannot be empty.";     
    } else {         
        $query = "INSERT INTO subjects (SubjectName) VALUES ('$subjectName')";         
        if (mysqli_query($conn, $query)) {             
            $message = "Subject added successfully.";          
        } else {             
            $errors[] = "Failed to add subject: " . mysqli_error($conn);         
        }     
    } 
}

if (isset($_POST['editSubject'])) {     
    $subjectID = mysqli_real_escape_string($conn, $_POST['subjectID']);     
    $newSubjectName = mysqli_real_escape_string($conn, $_POST['newSubjectName']);     

    if (empty($newSubjectName)) {         
        $errors[] = "New Subject Name cannot be empty.";     
    } else {         
        $query = "UPDATE subjects SET SubjectName='$newSubjectName' WHERE SubjectID='$subjectID'";         
        if (!mysqli_query($conn, $query)) {             
            $errors[] = "Failed to update subject: " . mysqli_error($conn);         
        } else {             
            $message = "Subject updated successfully.";          
        }     
    } 
}

if (isset($_POST['deleteSubject'])) {     
    $subjectID = mysqli_real_escape_string($conn, $_POST['subjectID']);     

    $query = "DELETE FROM subjects WHERE SubjectID='$subjectID'";     
    if (mysqli_query($conn, $query)) {         
        $message = "Subject deleted successfully.";     
    } else {         
        $errors[] = "Failed to delete subject: " . mysqli_error($conn);     
    } 
}

$subjects_query = "SELECT * FROM subjects"; 
$subjects_result = mysqli_query($conn, $subjects_query); 
if (!$subjects_result) {     
    die("Query failed: " . mysqli_error($conn)); 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Streams and Subjects</title>
    <link rel="stylesheet" href="styless.css">
    <style>
        .h1, .h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #fff; }
        thead { background-color: #333; color: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        tbody tr:nth-child(even) { background-color: #f9f9f9; }
        tbody tr:hover { background-color: #f1f1f1; }
        button { padding: 5px 10px; margin: 0 2px; border: none; cursor: pointer; border-radius: 4px; }
        .edit-btn { background-color: #343a40; color: white; }
        .delete-btn { background-color: #f44336; color: white; }
        .message { color: green; text-align: center; margin-bottom: 10px; }
        .error { color: red; text-align: center; }
    </style>
    <script>
        function hideMessage() {
            setTimeout(function() {
                var message = document.getElementById('message');
                if (message) { message.style.display = 'none'; }
            }, 10000);
        }
    </script>
</head>
<body onload="hideMessage();">
    <h1 class="h1">Manage Streams and Subjects</h1>
    
    <?php if ($message): ?>
        <div id="message" class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form action="" method="post">
        <h2 class="h2">Add Stream</h2>
        <label for="streamName">Stream Name:</label>
        <input type="text" id="streamName" name="streamName" required>
        <label for="associatedSubjects">Associated Subjects (comma-separated):</label>
        <input type="text" id="associatedSubjects" name="associatedSubjects" required placeholder="e.g. Mathematics, Science, English">
        <button type="submit" name="addStream">Add Stream</button>
    </form>
    
    <br>
    <h2 class="h2">Existing Streams</h2>
    <table>
        <thead>
            <tr>
                <th>Stream Name</th>
                <th>Associated Subjects</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($streams_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['StreamName']); ?></td>
                    <td><?php echo htmlspecialchars($row['Subjects']); ?></td>
                    <td>
                        <form action="" method="post" style="display: inline-block;">
                            <input type="hidden" name="streamID" value="<?php echo htmlspecialchars($row['StreamID']); ?>">
                            <input type="text" name="newStreamName" placeholder="Edit Stream Name">
                            <button type="submit" name="editStream" class="edit-btn">Edit</button>
                        </form>
                        <form action="" method="post" style="display: inline-block;">
                            <input type="hidden" name="streamID" value="<?php echo htmlspecialchars($row['StreamID']); ?>">
                            <button type="submit" name="deleteStream" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <form action="" method="post">
        <h2 class="h2">Add Subject</h2>
        <label for="subjectName">Subject Name:</label>
        <input type="text" id="subjectName" name="subjectName" required>
        <button type="submit" name="addSubject">Add Subject</button>
    </form>
    
    <br>
    <h2 class="h2">Existing Subjects</h2>
    <table>
        <thead>
            <tr>
                <th>Subject Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($subjects_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['SubjectName']); ?></td>
                    <td>
                        <form action="" method="post" style="display: inline-block;">
                            <input type="hidden" name="subjectID" value="<?php echo htmlspecialchars($row['SubjectID']); ?>">
                            <input type="text" name="newSubjectName" placeholder="Edit Subject Name">
                            <button type="submit" name="editSubject" class="edit-btn">Edit</button>
                        </form>
                        <form action="" method="post" style="display: inline-block;">
                            <input type="hidden" name="subjectID" value="<?php echo htmlspecialchars($row['SubjectID']); ?>">
                            <button type="submit" name="deleteSubject" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
