<?php
session_start();
if($_SESSION['login'] != "success" || ($_SESSION['level'] != "admin" && $_SESSION['level'] != "volunteer")){
  header('Location: ../../login.php');
}

if($_SERVER['REQUEST_METHOD']=='POST'){

  require_once("../../../connections/db_connect.php");

  $link = mysqli_real_escape_string($conn, $_POST['weekly-meeting-link']);
  $sql = "UPDATE meeting_rooms SET link='$link' WHERE name='Weekly Meeting'";

  $query = mysqli_query($conn, $sql);
  if($query){
    echo "success";
  }else{
    echo "Update failed";
  }

}else{
  die("");
}
