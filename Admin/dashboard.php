
<?php
if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
 
<div class="cards">
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="card-content">
            <h3>Students</h3>
            <p><?php echo $student_count; ?></p>
        </div>
    </div>
 
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="card-content">
            <h3>Parents</h3>
            <p><?php echo $parent_count; ?></p>
        </div>
    </div>
 
    <div class="card">
    <div class="card-icon">
        <i class="fas fa-chalkboard-teacher"></i>
    </div>
    <div class="card-content">
        <h3>Teachers</h3>
        <p><?php echo $teacher_count; ?></p>
    </div>
</div>

    </div>
<div class="">
    <a href="../Admin/new.php">
        <button>Backup</button>
    </a>
</div>


</body>
</html>