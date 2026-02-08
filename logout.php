<?php
// Logout page - destroys session and redirects to login
require_once 'includes/config.php';

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: index.php');
exit();
?>
