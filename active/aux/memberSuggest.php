<?php
// This page returns a list of members matching the search query as JSON
// query to page:
// POST, parameters: string = search string

session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") die("invalid access method");

  //connecting to the database
  require_once("../../connections/db_connect.php");

  $suggestions = array();

  $sql="";

  $searchString = str_replace("@","",mysqli_real_escape_string($conn, $_POST['string']));

  if(isset($_POST['level'])){
    $sql = "select * from users where level='" . $_POST['level'] . "' and (fname like '%" . $searchString . "%' or lname like '%" . $searchString . "%' or username like '%" . $searchString . "%' or email like '%" . $searchString . "%')";
  }else{
    $sql = "select * from users where (fname like '%" . $searchString . "%' or lname like '%" . $searchString . "%' or username like '%" . $searchString . "%' or email like '%" . $searchString . "%')";
  }

  $query = mysqli_query($conn, $sql);

  while($user = mysqli_fetch_array($query)){
    // for each matched user = $user
    array_push($suggestions,array("fname"=>$user['fname'], "lname"=> $user['lname'], "username"=>$user['username']));

  }

  // echo $sql;

  echo json_encode($suggestions);

?>
