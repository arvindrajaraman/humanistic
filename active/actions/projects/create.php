<?php
session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") {
  header('Location: ../../projects.php');
};

  require_once("../../../connections/db_connect.php");

  if(trim($_POST['project-name'])=="") {
    $_SESSION['errors']['project-creation']="Invalid name";
    header('Location: ../../projects.php');
    die();
  }

  $fmedia = mysqli_real_escape_string($conn, "<img class='project-fmedia' src='https://generative-placeholders.glitch.me/image?width=1000&height=700&style=cellular-automata&cells=100&colors=" . rand(0,100) . ">' alt='' />");

  $sql = "INSERT INTO projects (name, creator_id, public, fmedia) VALUES ('" . $_POST['project-name'] . "', '" . $_SESSION['id'] . "', 0, '" . $fmedia . "')";
  $request = mysqli_query($conn, $sql);

  $sql = "INSERT INTO projects_collab (name, creator_id, public, fmedia) VALUES ('" . $_POST['project-name'] . "', '" . $_SESSION['id'] . "', 0, '" . $fmedia . "')";
  $request = mysqli_query($conn, $sql);

  if(!$request){
    echo "failure" . mysqli_error($conn);
  }else{

    $sql = "SELECT * from projects WHERE name='" . $_POST['project-name'] . "' and creator_id='" . $_SESSION['id'] . "' and public=0";
    $request = mysqli_query($conn, $sql);

    $project=mysqli_fetch_array($request);

    $target_dir = "../../../resources/files/projects/" . $project['id'] . "/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    $sql = "INSERT into project_members (project_id, user_id, member_role) VALUES ('" . $project['id'] . "', '" . $_SESSION['id'] . "', 'member')";
    $request = mysqli_query($conn, $sql);

    $sql = "INSERT into project_members_collab (project_id, user_id, member_role) VALUES ('" . $project['id'] . "', '" . $_SESSION['id'] . "', 'member')";
    $request = mysqli_query($conn, $sql);

    header('Location: ../../projects.php');

  }



  // $sql = "update ";
?>
