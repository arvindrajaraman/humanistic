<?php
session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") {
  header('Location: ../../profile.php');
};

require_once("../../../connections/db_connect.php");

$about = mysqli_real_escape_string($conn,$_POST['about']);
$whereabouts = mysqli_real_escape_string($conn,$_POST['whereabouts']);
$wa_lat = mysqli_real_escape_string($conn, $_POST['wa_lat']);
$wa_lng = mysqli_real_escape_string($conn, $_POST['wa_lng']);

$co_designer = 0;

if(isset($_POST['co_designer']) && $_POST['co_designer']==1){
  $co_designer=1;
}

// print_r($_POST);

$sql = "UPDATE users SET co_designer='" . $co_designer . "', about='" . $about . "', whereabouts='" . $whereabouts . "', wa_lat='$wa_lat', wa_lng='$wa_lng' WHERE id='" . $_SESSION['id'] . "'";
$request = mysqli_query($conn, $sql);

if($request){
  echo "success";
}else{
  echo "failed";
}

?>
