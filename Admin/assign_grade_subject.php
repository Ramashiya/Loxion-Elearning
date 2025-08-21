<?php
include('../Database/database_connection.php');
session_start();

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['assign'])) {
        $teacher_id = $_POST['teacher_id'];
        $grade = $_POST['grade'];
        $subjects = $_POST['subjects'];

        $assignment_query = "INSERT INTO teacher_assignments (TeacherID, SubjectID, Grade) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $assignment_query);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
        }

        $success = true;

        foreach ($subjects as $subject_id) {
            mysqli_stmt_bind_param($stmt, 'iis', $teacher_id, $subject_id, $grade);
            if (!mysqli_stmt_execute($stmt)) {
                $success = false;
                echo 'Execute failed: ' . htmlspecialchars(mysqli_stmt_error($stmt));
                break;
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        if ($success) {
            echo 'Assignments were successfully made.';
        } else {
            echo 'There was an error making the assignments.';
        }
    }

    if (isset($_POST['remove'])) {
        $teacher_id = $_POST['teacher_id'];
        $subjects = $_POST['subjects_to_remove'];

        $remove_query = "DELETE FROM teacher_assignments WHERE TeacherID = ? AND SubjectID = ?";
        $stmt = mysqli_prepare($conn, $remove_query);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
        }

        $success = true;

        foreach ($subjects as $subject_id) {
            mysqli_stmt_bind_param($stmt, 'ii', $teacher_id, $subject_id);
            if (!mysqli_stmt_execute($stmt)) {
                $success = false;
                echo 'Execute failed: ' . htmlspecialchars(mysqli_stmt_error($stmt));
                break;
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        if ($success) {
            echo 'Assignments were successfully removed.';
        } else {
            echo 'There was an error removing the assignments.';
        }
    }

    exit();
}
?>
