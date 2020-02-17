<?php
session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") die("invalid access method");

$fmedia_options = scandir("../../../../resources/files/projects/" . $_POST['project-id'] . "/");
if (($key = array_search('.', $fmedia_options)) !== false) {
  unset($fmedia_options[$key]);
}
if (($key = array_search('..', $fmedia_options)) !== false) {
  unset($fmedia_options[$key]);
}
if (($key = array_search('.DS_Store', $fmedia_options)) !== false) {
  unset($fmedia_options[$key]);
}

echo json_encode($fmedia_options);

?>
