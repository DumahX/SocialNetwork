<?php
// initialize the session
session_start();

// unset all session variables
$_SESSION = array();

// destroy session
session_destroy();

// redirect to login
header("location: login.php");
exit;
?>