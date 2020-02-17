<?php
// This page returns a list of project members as JSON
// query to page:
// POST, parameters: id = project's id

session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") die("invalid access method");

  //connecting to the database
  require_once("../../connections/db_connect.php");

  $members = array();

  $project_id = $_POST['id'];
  // $project_id = 1;

  $sql = "select fname, lname, users.id, username from project_members, users where project_members.user_id = users.id and project_members.project_id = '" . $project_id . "'";
  $query = mysqli_query($conn, $sql);
  while($member = mysqli_fetch_array($query)){
    //for each project member = $member
    array_push($members,array("fname"=>$member['fname'], "lname"=> $member['lname'], "username"=>$member['username']));

  }

  echo json_encode($members);

?>
