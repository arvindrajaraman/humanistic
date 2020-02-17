<?php
session_start();
if($_SESSION['login'] != "success" || ($_SESSION['level'] != "admin" && $_SESSION['level'] != "volunteer")){
  header('Location: ../../login.php');
}

if($_SERVER['REQUEST_METHOD']=='POST'){

  require_once("../../../connections/db_connect.php");

  $id = $_POST['id'];
  $category = $_POST['category'];

  if(isset($_POST['remove'])){
    $sql = "DELETE FROM users WHERE id='" . $id . "' and level='" . $category . "'";
    $request = mysqli_query($conn, $sql);

  } else if(isset($_POST['update'])){
    $sql = "UPDATE users set fname='" . $_POST['fname'] . "', lname='" . $_POST['lname'] . "', username='" . $_POST['username'] . "', email='" . $_POST['email'] . "' WHERE id='" . $id . "' and level='" . $category . "'";
    $request = mysqli_query($conn, $sql);

  }

  //echo "reached";
  header('Location: ../../community.php');

}else{
  header('Location: ../../community.php');
}
