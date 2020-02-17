<?php

session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") die("invalid access method");

$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt', 'mp4'); // valid extensions
$target_dir = "./../../../../resources/files/projects/" . $_POST['project-id'] . "/"; // upload directory
$path = $target_dir;

if($_FILES['image'])
{
  $img = $_FILES['image']['name'];
  $tmp = $_FILES['image']['tmp_name'];

  // get uploaded file's extension
  $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

  // can upload same image using rand function
  // $final_image = rand(1000,1000000).$img;
  $final_image = $img;

  // check's valid format
  if(in_array($ext, $valid_extensions) || TRUE)
  {
    $path = $path.strtolower($final_image);

    if(move_uploaded_file($tmp,$path))
    {
      echo "<img src='$path' />";

    }
  }
  else
  {
    echo 'invalid';
  }
}
?>
