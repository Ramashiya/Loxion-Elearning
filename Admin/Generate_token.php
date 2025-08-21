<?php
include('../Database/database_connection.php');
session_start();

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $grade = isset($_POST['grade']) ? $_POST['grade'] : NULL;

    $token = bin2hex(random_bytes(3));

    if ($role === 'student') {
        $classrooms = ['A', 'B', 'C', 'D'];
        $selectedClassroom = $classrooms[array_rand($classrooms)];
    } else {
        $selectedClassroom = NULL; 
    }

    $currentYear = date("Y");
    $expirationDate = "$currentYear-12-19 23:59:59";

    if (date("Y-m-d") > "$currentYear-12-19") {
        $expirationDate = ($currentYear + 1) . "-12-19 23:59:59";
    }

    $query = "INSERT INTO pre_registration_tokens (Token, Role, Grade, Classroom, GeneratedOn, ExpiresOn) VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt === false) {
        echo "<script>alert('Prepare failed: " . mysqli_error($conn) . "');</script>";
    } else {
        mysqli_stmt_bind_param($stmt, "sssss", $token, $role, $grade, $selectedClassroom, $expirationDate);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Token generated successfully: $token');</script>";
        } else {
            echo "<script>alert('Execute failed: " . mysqli_stmt_error($stmt) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>
