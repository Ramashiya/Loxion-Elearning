<?php
session_start();
ob_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            var userConfirmed = confirm("Do you want to log out?");
            if (userConfirmed) {
                window.location.href = event.target.href;
            } else {
                return false;
            }
        }
    </script>
<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    display: flex;
    height: 100vh;
    background-color: #f4f4f4;
    overflow: hidden; /* Prevent scrolling for better layout control */
}

/* Sidebar styling */
.sidebar {
    width: 250px; 
    background-color: #343a40;
    color: #fff;
    flex-shrink: 0; 
    height: 100vh; 
    position: fixed; 
    top: 0; 
    left: 0; /
    transition: transform 0.3s ease;
}

/* Main content styling */
.main-content {
    flex-grow: 1; 
    padding: 20px;
    margin-left: 250px; 
    background-color: #fff;
    height: 100vh; 
    overflow-y: auto; 
    transition: margin-left 0.3s ease; 
}


@media (max-width: 768px) {
    .main-content {
        margin-left: 0; 
        padding: 15px; 
    }

    .sidebar {
        width: 100%; 
        height: auto; 
        position: relative; /* Change position for mobile */
    }
}

.sidebar-header {
    padding: 20px;
    background-color: #343a40; /*background for the word ADMIN DASHBOARD */
    text-align: center;
}

.sidebar-nav ul {
    list-style: none;
    padding: 0;
}

.sidebar-nav ul li {
    margin: 10px 0;
}

.sidebar-nav ul li a {
    display: block;
    padding: 6px 15px; 
    font-size: 0.9em; 
    color: #fff; /* NAMES next to icons */
    text-decoration: none;
    transition: background 0.3s;
}



.sidebar-nav ul li a:hover {
    background-color: #495057; /*color of hover */
}

.sidebar-nav ul li a i {
    margin-right: 10px;
}

/* Main content styling */


.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #e9ecef; /*header for WELCOME ADMIN */
    border-bottom: 1px solid #dee2e6;
}

.header-actions .logout-btn {
    padding: 10px 20px;
    background-color: #343a40;
    color: #fff;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.header-actions .logout-btn:hover {
    background-color: #495057;
}

.dashboard-content {
    padding: 20px;
}

.cards {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.card {
    flex: 1;
    margin: 10px;
    padding: 20px;
    background-color: #fff;/*cards for admin, student and parent */
    border: 1px solid #dee2e6;
    border-radius: 5px;
    display: flex;
    align-items: center;
    transition: box-shadow 0.3s;
}

.card:hover {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
}

.card-icon {
    font-size: 2em;
    margin-right: 20px;
    color: #343a40;
}

.card-content h3 {
    margin-bottom: 10px;
    font-size: 1.2em;
}

.card-content p {
    font-size: 1.5em;
    font-weight: bold;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .main-content {
        margin-left: 0;
    }

    .sidebar-nav ul li {
        display: inline-block;
        margin: 0;
    }

    .sidebar-nav ul li a {
        padding: 10px 15px;
        font-size: 0.9em;
    }

    .header {
        flex-direction: column;
        align-items: flex-start;
    }

    .cards {
        flex-direction: column;
    }

    .card {
        width: 100%;
        margin: 10px 0;
    }
}

@media (max-width: 480px) {
    .header {
        padding: 10px;
    }

    .header h1 {
        font-size: 1.2em;
    }

    .card-icon {
        font-size: 1.5em;
    }

    .card-content p {
        font-size: 1.2em;
    }

    .sidebar-nav ul li a {
        padding: 10px 10px;
    }
}


    </style>
</head>
<style>
  

    /* Button styling back up*/
    button {
        margin: 5px;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007BFF;
        color: white;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }


</style>

<body>
 
<script>
document.addEventListener('DOMContentLoaded', () => {
    let logoutTimer;
    let warningTimer;
    let countdownTimer;
    let countdownElement;

    function startTimers() {
        clearTimeout(logoutTimer);
        clearTimeout(warningTimer);
        clearInterval(countdownTimer);

        if (countdownElement) {
            countdownElement.style.display = 'none'; 
        }

        warningTimer = setTimeout(showWarning, 10000); // 1 minute of inactivity
    }

    function showWarning() {
        if (!countdownElement) {
            countdownElement = document.createElement('div');
            countdownElement.id = 'countdown';
            countdownElement.style.position = 'fixed';
            countdownElement.style.top = '10px';
            countdownElement.style.right = '10px';
            countdownElement.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
            countdownElement.style.color = '#fff';
            countdownElement.style.padding = '10px';
            countdownElement.style.borderRadius = '5px';
            countdownElement.style.zIndex = '1000';
            document.body.appendChild(countdownElement);
        }

        let countdown = 10; // Start countdown from 10 seconds
        countdownElement.textContent = `You will be logged out in ${countdown} seconds due to inactivity.`;
        countdownElement.style.display = 'block';

        countdownTimer = setInterval(() => {
            countdown--;
            countdownElement.textContent = `You will be logged out in ${countdown} seconds due to inactivity.`;

            if (countdown <= 0) {
                clearInterval(countdownTimer);
                logoutUser();
            }
        }, 1000);

        logoutTimer = setTimeout(logoutUser, 10000); // 10 seconds
    }

    function logoutUser() {
        if (countdownElement) {
            countdownElement.style.display = 'none'; 
        }
        window.location.href = '../Login_Logout/logout.php';
    }

    function resetTimersOnActivity() {
        clearTimeout(logoutTimer);
        clearTimeout(warningTimer);
        clearInterval(countdownTimer);
        
        if (countdownElement) {
            countdownElement.style.display = 'none'; 
        }

        startTimers(); 
    }

    ['mousemove', 'keypress', 'click'].forEach(eventType => {
        document.addEventListener(eventType, resetTimersOnActivity);
    });

    startTimers();
});
</script>

<?php
include('../session/session_check.php');

include('../Database/database_connection.php');

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login_Logout/logout.php');
    exit();
}
$user_id = $_SESSION['UserID'];
$sql = "SELECT Name FROM users WHERE UserID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $admin_name);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$result = $conn->query("SELECT COUNT(*) AS count, Role FROM users GROUP BY Role");
$counts = [];
while ($row = $result->fetch_assoc()) {
    $counts[$row['Role']] = $row['count'];
}

$student_count = isset($counts['student']) ? $counts['student'] : 0;
$teacher_count = isset($counts['teacher']) ? $counts['teacher'] : 0;
$parent_count = isset($counts['parent']) ? $counts['parent'] : 0;
?>
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Dashboard</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="?page=dashboard"><i class="fa-solid fa-users-gear"></i> Users</a></li>
                    <li><a href="?page=teacher"><i class="fa-solid fa-chalkboard-user"></i>Teachers</a></li>
                    <li><a href="?page=course"><i class="fa-solid fa-book"></i>Subjects</a></li>
                    <li><a href="?page=attendance"><i class="fa-solid fa-triangle-exclamation"></i>Attendance</a></li>
                    <li><a href="?page=schedule"><i class="fa-duotone fa-solid fa-clipboard-list"></i>Schedule</a></li>
                    <li><a href="?page=Assignments"><i class="fa-solid fa-list-check"></i>Assignments</a></li>
                    <li><a href="?page=notifications"><i class="fa-brands fa-rocketchat"></i> Messages</a></li>
                    <li><a href="?page=Events"><i class="fa-solid fa-handshake"></i>Events</a></li>
                    <li><a href="?page=results"><i class="fa-solid fa-square-poll-vertical"></i>Results</a></li>
                    <li><a href="?page=behavior"><i class="fa-regular fa-flag"></i>Behavior Reports</a></li>
                    <li><a href="?page=pre_registration"><i class="fas fa-key"></i>Pre registration</a></li>
                    <li><a href="?page=manage_users"><i class="fa-solid fa-user-cog"></i> Manage Users</a></li>
                    <li><a id="logout-btnn" href="../Login_Logout/logout.php" onclick="return confirmLogout(event)"><i class="fas fa-sign-out-alt"></i> Logout</a></li>            </nav>
        </aside>
        <main class="main-content">
            <header class="header">
                <h1>Welcome  <?php echo htmlspecialchars($admin_name); ?></h1>
                <div class="header-actions">
                <li><a href="../Login_Logout/logout.php" onclick="return confirmLogout(event)"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

                  
                </div>
            </header>
            <section class="dashboard-content">
                <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                switch ($page) {
          
                    case 'timetable':
                        include 'timetable.php';
                        break;
                    case 'teacher':
                        include 'teachers_management.php';
                        break;
                        
                    case 'Events':
                        include 'Events.php';
                        break;
                        
                    case 'behavior':
                        include 'behavior.php';
                        break;
                    case 'course':
                        include 'course.php';
                        break;
                    case 'Assignments':
                        include 'manage_assignments.php';
                        break;
                    case 'schedule':
                        include 'schedule_admin.php';
                        break;
                    case 'attendance':
                            include 'admin_attendance.php';
                            break;
                    case 'asignments':
                            include 'assignments.php';
                            break; 
                    case 'results':
                        include 'results.php';
                        break;
                    case 'pre_registration':
                        include 'pre_registration.php';
                        break;
                    case 'notifications':
                        include 'notifications.php';
                        break;
                    case 'manage_users':
                        include 'manage_users.php';
                        break;
                    default:
                        include 'dashboard.php';
                }
                ?>
            </section>
        </main>
    </div>
</body>
</html>
