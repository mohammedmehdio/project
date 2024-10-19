<?php
session_start(); // Start the session

// Destroy all session variables
$_SESSION = array();

// If a session cookie exists, delete it
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session itself
session_destroy();

// Redirect to the login page or homepage
header("Location: ../index.html"); // You can change this to the homepage if needed
exit;
?>
