<?php
include('../Database/database_connection.php');

$streamID = null;
$selected_subjects = [];
$message = "";

$query_teachers = "SELECT * FROM users WHERE Role = 'teacher'";
$teachers_result = mysqli_query($conn, $query_teachers);

$grades = array('Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12');

$query_streams = "SELECT * FROM streams";
$streams_result = mysqli_query($conn, $query_streams);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacherID = $_POST['teacher'];
    $grade = $_POST['grade'];

    if ($grade == 'Grade 8' || $grade == 'Grade 9') {
        $query_subjects = "SELECT SubjectID, SubjectName FROM subjects";
        $result = mysqli_query($conn, $query_subjects);

        while ($row = mysqli_fetch_assoc($result)) {
            $selected_subjects[] = $row; 
        }
    } 
    else {
        $streamID = isset($_POST['stream']) ? $_POST['stream'] : null;
        if (!empty($streamID)) {
            $query_subjects = "SELECT ss.SubjectID, ss.SubjectName 
            FROM subject_streams ss
            WHERE ss.StreamID = ?";

            $stmt = mysqli_prepare($conn, $query_subjects);
            mysqli_stmt_bind_param($stmt, 'i', $streamID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $selected_subjects[] = $row; 
            }
        }
    }

    if (isset($_POST['subject']) && !empty($_POST['subject'])) {
        $subjectID = $_POST['subject'];
   $subject_name = '';
            foreach ($selected_subjects as $subject) {
                if ($subject['SubjectID'] == $subjectID) {
                    $subject_name = $subject['SubjectName'];
                    break; 
                }
            }
        $check_query = "SELECT u.Name, u.Lastname FROM teacher_assignments ta
                        JOIN users u ON ta.TeacherID = u.UserID
                        WHERE ta.SubjectID = ? AND ta.Grade = ?";
        
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, 'is', $subjectID, $grade);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        $assigned_teachers = [];
        while ($row = mysqli_fetch_assoc($check_result)) {
            $assigned_teachers[] = $row['Name'] . " " . $row['Lastname'];
        }

        $current_check_query = "SELECT * FROM teacher_assignments WHERE TeacherID = ? AND SubjectID = ? AND Grade = ?";
        $current_check_stmt = mysqli_prepare($conn, $current_check_query);
        mysqli_stmt_bind_param($current_check_stmt, 'iis', $teacherID, $subjectID, $grade);
        mysqli_stmt_execute($current_check_stmt);
        $current_check_result = mysqli_stmt_get_result($current_check_stmt);

        if (mysqli_num_rows($current_check_result) > 0) {
            $teachers_list = implode(", ", $assigned_teachers);
            $message = "<div class='error-message' id='error-message'>$teachers_list is already assigned to $subject_name for $grade!</div>";        } else if (count($assigned_teachers) > 0) {
            $teachers_list = implode(", ", $assigned_teachers); 
            $message = "<div class='error-message'>$teachers_list is already assigned to this subject for $grade!</div>";
        } else {
            $query = "INSERT INTO teacher_assignments (TeacherID, SubjectID, Grade) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'iis', $teacherID, $subjectID, $grade);
            if (mysqli_stmt_execute($stmt)) {
                $message = "<div class='success-message'>Teacher successfully assigned!</div>";
            } else {
                $message = "<div class='error-message'>Error: " . mysqli_error($conn) . "</div>";
            }
        }
    } else {
        $message = "<div class='error-message'>Please select a subject.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Teacher to Grade and Subject</title>
    <style>
      

       
     .h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        select, input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }

        button {
            padding: 12px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .submit-btn {
            align-self: center;
            width: 100%;
            max-width: 200px;
        }

        .success-message, .error-message {
            text-align: center;
            padding: 10px;
            color: white;
            border-radius: 5px;
            margin-bottom: 20px;
            display: block;
        }

        .success-message {
            background-color: #28a745;
        }

        .error-message {
            background-color: #dc3545;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            h2.h2 {
                font-size: 1.5em;
            }

            label, select, input, button {
                font-size: 1em;
                padding: 8px;
            }

            button {
                padding: 10px;
            }
        }
    </style>
    <script>
        function showMessage() {
            const successMessage = document.querySelector('.success-message');
            const errorMessage = document.querySelector('.error-message');

            if (successMessage && successMessage.innerHTML) {
                successMessage.style.display = 'block';
                setTimeout(() => { successMessage.style.display = 'none'; }, 5000);
            } else if (errorMessage && errorMessage.innerHTML) {
                errorMessage.style.display = 'block';
                setTimeout(() => { errorMessage.style.display = 'none'; }, 5000);
            }
        }

        window.onload = showMessage; 
    </script>
</head>
<body>
    <div class="container">
        <h2 class="h2">Assign Teacher to Grade and Subject</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST">
            <label for="teacher">Select Teacher:</label>
            <select name="teacher" id="teacher" required>
                <?php while ($row = mysqli_fetch_assoc($teachers_result)) {
                    echo "<option value='" . $row['UserID'] . "'>" . $row['Name'] . " " . $row['Lastname'] . "</option>";
                } ?>
            </select>
            
            <label for="grade">Select Grade:</label>
            <select name="grade" id="grade" onchange="this.form.submit()" required>
                <?php foreach ($grades as $grade) {
                    echo "<option value='$grade' ".(isset($_POST['grade']) && $_POST['grade'] === $grade ? 'selected' : '').">$grade</option>";
                } ?>
            </select>

            <?php if (isset($_POST['grade']) && in_array($_POST['grade'], ['Grade 10', 'Grade 11', 'Grade 12'])): ?>
                <label for="stream">Select Stream (Grade 10-12):</label>
                <select name="stream" id="stream" onchange="this.form.submit()">
                    <option value="">--Select Stream--</option>
                    <?php while ($row = mysqli_fetch_assoc($streams_result)) {
                        $selected = ($streamID == $row['StreamID']) ? 'selected' : '';
                        echo "<option value='" . $row['StreamID'] . "' $selected>" . $row['StreamName'] . "</option>";
                    } ?>
                </select>
            <?php endif; ?>

            <?php if (!empty($selected_subjects)): ?>
                <label for="subject">Select Subject:</label>
                <select name="subject" id="subject" required>
                    <option value="">--Select Subject--</option>
                    <?php foreach ($selected_subjects as $subject) {
                        echo "<option value='" . $subject['SubjectID'] . "'>" . $subject['SubjectName'] . "</option>";
                    } ?>
                </select>
            <?php endif; ?>

            <button type="submit" class="submit-btn">Assign Teacher</button>
        </form>
    </div>
</body>
</html>
