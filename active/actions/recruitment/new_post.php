<?php
session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") {
  header('Location: ../../projects.php');
};

require_once("../../../connections/db_connect.php");

$project_id = mysqli_real_escape_string($conn, $_POST['project-id']);
$role = mysqli_real_escape_string($conn, $_POST['role']);
$responsibilities = mysqli_real_escape_string($conn, $_POST['responsibilities']);
$eligibility = mysqli_real_escape_string($conn, $_POST['eligibility']);

$sql = "INSERT into project_recruitment (role, responsibilities, project_id, poster_id, co_designers_only) values('" . $role . "', '" . $responsibilities . "', '" . $project_id . "', '" . $_SESSION['id'] . "', '" . $eligibility . "')";
mysqli_query($conn, $sql);

$_SESSION['messages']['new-recruitment'] = "Successfully posted!";

header('Location: ../../projects.php');

?>
