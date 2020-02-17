<?php
session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") {
  header('Location: ../../projects.php');
};

require_once("../../../connections/db_connect.php");

$posting_id = $_POST['posting_id'];

$sql = "SELECT * from project_recruitment WHERE id='$posting_id'";
$request = mysqli_query($conn, $sql);
$project_id = mysqli_fetch_array($request)["project_id"];

$sql = "SELECT * from project_members where project_id = '" . $project_id . "' and user_id = '" . $_SESSION['id'] . "'";
$valid_project_member = mysqli_num_rows(mysqli_query($conn, $sql))>0;

if($valid_project_member){

  $sql = "DELETE FROM  project_recruitment WHERE id='$posting_id'";
  $request = mysqli_query($conn, $sql);

  if(!$request){
    $_SESSION['messages']['delete-recruitment'] = "Deletion failed!";
  }else{
    $_SESSION['messages']['delete-recruitment'] = "";
  }

}

header('Location: ../../projects.php');

?>
