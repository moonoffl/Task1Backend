<?php
session_start();

if (!isset($_SESSION['UserName'])) {
    header("Location: ../Sign_in/Sign_in.php");
    exit;
}
if (!isset($_SESSION['User_Id'])) {
    header("Location: ../Sign_in/Sign_in.php");
    exit;
}
?>
