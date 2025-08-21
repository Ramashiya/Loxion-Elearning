<?php

$timeout_duration = 5 * 60; 

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header('Location: ../Login_Logout/logout.php');
    exit();
}

$_SESSION['last_activity'] = time();
?>
