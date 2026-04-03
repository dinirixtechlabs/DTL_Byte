<?php
session_start();
session_unset();     // remove all session variables
session_destroy();   // destroy session

header("Location: index.php"); // redirect to home/login page
exit();
?>