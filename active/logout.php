<?php
// This is the (public) page to logout

session_start();

$_SESSION['name']="";
$_SESSION['fname']="";
$_SESSION['lname']="";
$_SESSION['email']="";
$_SESSION['login']="failed";
session_unset();
session_destroy();
header('Location: ../');
exit();
?>
