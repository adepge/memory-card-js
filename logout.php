<?php
    session_start();
    if (isset($_SESSION['user_name'])) {
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        session_destroy();
		setcookie("logged_in", "", time() - 3600);
    }
    header('Location: index.php');
    exit;
?>