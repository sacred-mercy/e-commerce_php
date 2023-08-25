<?php
// unset and destroy session
session_start();
session_unset();
session_destroy();

// redirect to login page
header('Location: login.php');
exit();
?>
