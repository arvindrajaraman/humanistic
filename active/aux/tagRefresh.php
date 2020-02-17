<?php
// This page returns a list of project tags as JSON
// query to page:
// POST, parameters: id = project's id

session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") die("invalid access method");

  //connecting to the database
  require_once("../../connections/db_connect.php");

  $project_id = $_POST['id'];
  // $project_id = 1;

  $sql = "select tags from projects where id = '" . $project_id . "'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query)["tags"];

  //exploding the string stored in database to get individual tags
  $tags = array_filter(explode(",",$result));

  echo json_encode($tags);

?>
