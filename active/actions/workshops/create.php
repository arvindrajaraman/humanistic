<?php
session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") {
  header('Location: ../../workshops.php');
};

//workshops can be created only by admins
if($_SESSION['level']!='admin') die();

require_once("../../../connections/db_connect.php");


if(trim($_POST['workshop-name'])=="") {
  $_SESSION['errors']['workshop-creation']="Invalid name";
  header('Location: ../../workshops.php');
  die();
}else{
  $workshopName=mysqli_real_escape_string($conn, $_POST['workshop-name']);
}

$fmedia = mysqli_real_escape_string($conn, "<img class='workshop-fmedia' src='https://generative-placeholders.glitch.me/image?width=1000&height=700&style=cellular-automata&cells=100&colors=" . rand(0,100) . ">' alt='' />");

$sql = "INSERT INTO workshops (name, creator_id, public, registration_questions, fmedia) VALUES ('" . $workshopName . "', '" . $_SESSION['id'] . "', 0, '{}', '" . $fmedia . "')";
  $request = mysqli_query($conn, $sql);

  if(!$request){
    echo "failure" . mysqli_error($conn);
  }else{

    $sql = "SELECT * from workshops WHERE name='" . $workshopName . "' and creator_id='" . $_SESSION['id'] . "' and public=0";
    $request = mysqli_query($conn, $sql);

    $response=mysqli_fetch_array($request);

    $target_dir = "../../../resources/files/workshops/" . $response['id'] . "/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }


    header('Location: ../../workshops.php');

  }



  // $sql = "update ";
  ?>
