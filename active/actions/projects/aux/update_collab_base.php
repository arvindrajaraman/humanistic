<?php
session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") die("invalid access method");

require_once("../../../../connections/db_connect.php");
// $about = str_replace('\r','',str_replace(PHP_EOL,'<br />',mysqli_real_escape_string($conn,$_POST['about'])));

$project_name = mysqli_real_escape_string($conn, $_POST['project-name']);
$project_id = $_POST['project-id'];
$about = mysqli_real_escape_string($conn, $_POST['about']);
$public_visibility = $_POST['visibility'];
$stage = $_POST['stage'];
$summary = mysqli_real_escape_string($conn,$_POST['summary']);
$notes = mysqli_real_escape_string($conn,$_POST['notes']);

if(isset($_POST['project-tag']))
$tags = implode(",",array_map(function ($tag) { return mysqli_real_escape_string($GLOBALS["conn"], $tag); },$_POST['project-tag']));
else $tags="";

$no_members=0;
if(isset($_POST['project-member']))
$project_members = array_map(function ($member) { return mysqli_real_escape_string($GLOBALS["conn"], $member); },$_POST['project-member']);
else
{$project_members = []; $no_members=1;}

$fmedia = $_POST['fmedia'];

if($fmedia != "unchanged"){
if($fmedia == "external") {
  $fmedia = $_POST['fmedia-external'];

  //removing dimension restrictions
  $fmedia = preg_replace("([ ]*((width)|(height))[ ]*=[ ]*['\"][0-9]*['\"])","",$fmedia);
}
else{
  $ext = strtolower(pathinfo($fmedia, PATHINFO_EXTENSION));

  $supported_images = Array("jpg","jpeg","png","gif","bmp");
  $supported_videos = Array("mp4");

  if(in_array($ext, $supported_images)){
    //supported image
    $fmedia = "<img class='project-fmedia' src='resources/files/projects/" . $project_id . "/" . $fmedia . "' alt='" . "' />";
  } elseif(in_array($ext, $supported_videos)){
    //supported video
    $fmedia = "<video class='project-fmedia' controls>" .
    "<source src='resources/files/projects/" . $project_id . "/" . $fmedia . "' type='video/mp4'>" .
    "Your browser does not support the video tag." .
    "</video>";
  }else{
    //unsupported image/video
  }
}

$fmedia = mysqli_real_escape_string($conn, $fmedia);
} else{
  $fmedia = -1;
}
//updating project members
foreach ($project_members as $value){
  $sql = "SELECT * FROM `users` where username = '" . $value . "'";
}

//removing removed members
$project_members_sql = "(" . implode(",",array_map(function ($v) { return "'" . $v . "'"; },$project_members)) . ")";

if($no_members){
  $sql = "DELETE from project_members_collab where project_id='" . $project_id . "'";
  $request = mysqli_query($conn, $sql);
}else{
  $sql = "SELECT users.username, users.id FROM `project_members`,`users` where project_members.user_id = users.id and project_id=" . $project_id . " and users.username NOT IN " . $project_members_sql;

  // echo $sql;

  if($request = mysqli_query($conn, $sql)){
    while ($removed = mysqli_fetch_array($request)){

      // echo "removing " . $removed['username'];

      $sql2 = "DELETE from project_members_collab where project_id='" . $project_id . "' AND user_id='" . $removed['id'] . "'";
      $request2 = mysqli_query($conn, $sql2);

    }
  }

}

//adding new members

$sql = "SELECT users.username, users.id FROM `project_members_collab`,`users` where project_members_collab.user_id = users.id and project_id=" . $project_id . " and users.username IN " . $project_members_sql;

if($request = mysqli_query($conn, $sql)){
  while ($present = mysqli_fetch_array($request)){
    if (($key = array_search($present['username'], $project_members)) !== false) {
      unset($project_members[$key]);
    }
  }
}

$project_members_sql = "(" . implode(",",array_map(function ($v) { return "'" . $v . "'"; },$project_members)) . ")";
$sql = "SELECT users.username, users.id FROM `users` where users.username IN " . $project_members_sql;

if($request = mysqli_query($conn, $sql)){
  while ($added = mysqli_fetch_array($request)){

    // echo "adding " . $added['username'];

    $sql2 = "INSERT into project_members_collab (project_id, user_id, member_role, accepted) values('" . $project_id . "', '" . $added['id'] . "', 'member', '0')";
    $request2 = mysqli_query($conn, $sql2);

  }
}

//member updating done

//updating other fields
if($fmedia != -1)
$sql = "UPDATE projects_collab set notes = '" . $notes . "', summary = '" . $summary . "', about = '" . $about . "', tags='" . $tags . "', stage='" . $stage . "', name='" . $project_name . "', fmedia='" . $fmedia . "', public='" . $public_visibility . "' where id='" . $_POST['project-id'] . "'";
else
$sql = "UPDATE projects_collab set notes = '" . $notes . "', summary = '" . $summary . "', about = '" . $about . "', tags='" . $tags . "', stage='" . $stage . "', name='" . $project_name . "', public='" . $public_visibility . "' where id='" . $_POST['project-id'] . "'";

$request = mysqli_query($conn, $sql);

if($request){
  echo "success";
}

// $sql = "update ";
// print_r($tags);
?>
