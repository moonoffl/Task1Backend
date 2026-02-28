<?php
session_start();
$_SESSION = [];
session_destroy();
header("Location: Sign_in.php");
exit;
?>
