<?php
session_start();      // Start the session
session_unset();      // Remove all session variables
session_destroy();    // Destroy the session completely

// Redirect to login page
header("Location: login.html");
exit;
?>
