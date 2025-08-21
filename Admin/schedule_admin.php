<?php
include('../Database/database_connection.php');

$teachers_query = "SELECT UserID, CONCAT(FirstName, ' ', Lastname) AS FullName FROM teachers";
$teachers_result = mysqli_query($conn, $teachers_query);
$teachers = mysqli_fetch_all($teachers_result, MYSQLI_ASSOC);

$selectedTeacherID = null;
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedTeacherID = mysqli_real_escape_string($conn, $_POST['teacher']);
    
    $subject_query = "
        SELECT 
            s.SubjectID, 
            s.SubjectName,
            ta.Grade
        FROM 
            teacher_assignments ta 
        JOIN 
            subjects s ON ta.SubjectID = s.SubjectID 
        WHERE 
            ta.TeacherID = ?";
    
    $stmt = mysqli_prepare($conn, $subject_query);
    
    if ($stmt === false) {
        die('MySQL prepare error: ' . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $selectedTeacherID);
    mysqli_stmt_execute($stmt);
    $subject_result = mysqli_stmt_get_result($stmt);
    $subjects = mysqli_fetch_all($subject_result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    if (isset($_POST['create_schedule'])) {
        $selectedSubjectID = mysqli_real_escape_string($conn, $_POST['subjects']);
        $time = mysqli_real_escape_string($conn, $_POST['time']);
        $day = mysqli_real_escape_string($conn, $_POST['day']); 

        $time_obj = DateTime::createFromFormat('H:i', $time);
        $start_time = DateTime::createFromFormat('H:i', '08:00');
        $end_time = DateTime::createFromFormat('H:i', '15:00'); 
        $lunch_start = DateTime::createFromFormat('H:i', '12:00');
        $lunch_end = DateTime::createFromFormat('H:i', '13:00'); 

        if ($time_obj < $start_time || $time_obj >= $end_time) {
            $error = "Schedules can only be set between 08:00 AM and 02:00 PM.";
        } elseif ($time_obj >= $lunch_start && $time_obj < $lunch_end) {
            $error = "Scheduling is not allowed during lunch time (12:00 PM to 1:00 PM).";
        } else {
            $existing_query = "
                SELECT * FROM schedule 
                WHERE subject = (SELECT SubjectName FROM subjects WHERE SubjectID = ?) 
                AND day = ?"; 
            
            $stmt = mysqli_prepare($conn, $existing_query);
            mysqli_stmt_bind_param($stmt, "is", $selectedSubjectID, $day);
            mysqli_stmt_execute($stmt);
            $existing_result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($existing_result) > 0) {
                $error = "This subject is already scheduled for this day. Please choose a different time.";
            } else {
                $selected_subject_query = "
                    SELECT 
                        s.SubjectName, 
                        ta.Grade 
                    FROM 
                        teacher_assignments ta 
                    JOIN 
                        subjects s ON ta.SubjectID = s.SubjectID 
                    WHERE 
                        ta.SubjectID = ?";
                
                $stmt = mysqli_prepare($conn, $selected_subject_query);
                
                if ($stmt === false) {
                    die('MySQL prepare error: ' . mysqli_error($conn)); 
                }

                mysqli_stmt_bind_param($stmt, "i", $selectedSubjectID);
                mysqli_stmt_execute($stmt);
                $subject_details_result = mysqli_stmt_get_result($stmt);
                $subject_details = mysqli_fetch_assoc($subject_details_result);
                mysqli_stmt_close($stmt);

                if ($subject_details) {
                    $insert_schedule_query = "INSERT INTO schedule (time, subject, grade, day) VALUES (?, ?, ?, ?)"; 
                    $stmt = mysqli_prepare($conn, $insert_schedule_query);
                    
                    if ($stmt === false) {
                        die('MySQL prepare error: ' . mysqli_error($conn)); 
                    }

                    $subjectName = $subject_details['SubjectName'];
                    $grade = $subject_details['Grade'];
                    mysqli_stmt_bind_param($stmt, "ssss", $time, $subjectName, $grade, $day); 
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $success = "Schedule created successfully!";
                        $error = '';  
                    } else {
                        $error = "Error creating schedule: " . mysqli_error($conn);
                        $success = ''; 
                    }
                } else {
                    $error = "Error retrieving subject details.";
                    $success = ''; 
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Schedule Management</title>
    <style>
        .schedule-management {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .schedule-management h1, 
        .schedule-management h2 {
            color: #333;
        }
        .schedule-management form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .schedule-management label {
            display: block;
            margin: 10px 0 5px;
        }
        .schedule-management select, 
        .schedule-management input[type="time"], 
        .schedule-management input[type="date"], 
        .schedule-management button {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .schedule-management button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .schedule-management button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
    <script>
        
        function setMinDate() {
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0'); 
            const yyyy = today.getFullYear();
            const minDate = yyyy + '-' + mm + '-' + dd; 
            document.getElementById("date").setAttribute("min", minDate);
        }

        
        function hideMessage(selector) {
            const message = document.querySelector(selector);
            if (message) {
                setTimeout(() => {
                    message.style.display = 'none';
                }, 5000);
            }
        }

        window.onload = function() {
            setMinDate();
            hideMessage('.error-message'); 
            hideMessage('.success-message'); 
        };
    </script>
</head>
<body>
<div class="schedule-management">
    <h2>Create Schedule</h2>
  
    
    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    
    <?php if ($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" id="scheduleForm">
        <label for="teacher">Select Teacher:</label>
        <select name="teacher" id="teacher" required onchange="this.form.submit()">
            <option value="">Select Teacher</option>
            <?php foreach ($teachers as $teacher): ?>
                <option value="<?php echo $teacher['UserID']; ?>" <?php echo ($selectedTeacherID == $teacher['UserID']) ? 'selected' : ''; ?>>
                    <?php echo $teacher['FullName']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="subjects">Subjects:</label>
        <select name="subjects" id="subjects" required>
            <option value="">Select Subject</option>
            <?php foreach ($subjects as $subject): ?>
                <option value="<?php echo $subject['SubjectID']; ?>">
                    <?php echo $subject['SubjectName'] . ' (' . $subject['Grade'] . ')'; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="time">Time:</label>
        <input type="time" name="time" id="time" required>

        <label for="day">Date:</label>
        <input type="date" name="day" id="date" required>

        <button type="submit" name="create_schedule">Create Schedule</button>
    </form>
</div>
</body>
</html>
