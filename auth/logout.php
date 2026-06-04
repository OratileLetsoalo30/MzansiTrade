<?php
session_start();


$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}


echo "<script>sessionStorage.removeItem('splash_viewed');</script>";


session_destroy();


echo "<script>window.location.href = 'login.php';</script>";
exit();
?>
