<?php
session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") die("invalid access method");

$file = $_POST['file'];
$project_id = $_POST['project-id'];
$target_dir = "./../../../../resources/files/projects/" . $project_id . "/";

if(unlink($target_dir.$file)){
  echo "success";
}else{
  echo "deletion failed";
}


?>
