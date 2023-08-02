<?php
// Start the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the admin login page after logout
header("Location: /gamermanagement/login.php");
exit();
?>