<?php
session_start();
session_unset();   // remove all session variables
session_destroy(); // destroy the session

// redirect to login page (the login form is inside member_dashboard.php itself)
header("Location: member_dashboard.php");
exit;
