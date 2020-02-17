<?php
session_start();
if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if($_SERVER['REQUEST_METHOD']!="POST") {
  header('Location: ../../workshops.php');
};

require_once("../../../connections/db_connect.php");
// $about = str_replace('\r','',str_replace(PHP_EOL,'<br />',mysqli_real_escape_string($conn,$_POST['about'])));

$workshop_name = mysqli_real_escape_string($conn, $_POST['workshop-name']);
$workshop_id = $_POST['workshop-id'];
$about = mysqli_real_escape_string($conn,$_POST['about']);
$public_visibility = $_POST['visibility'];
$accepting_registrations = $_POST['accepting'];
$registration_fee = $_POST['registration_fee'];

// $tags = implode(",",$_POST['workshop-tag']);
$no_managers = 0;
if(isset($_POST['workshop-manager']))
$workshop_managers = array_map(function ($member) { return mysqli_real_escape_string($GLOBALS["conn"], $member); },$_POST['workshop-manager']);
else
{$workshop_managers = []; $no_managers=1;}

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$location = mysqli_real_escape_string($conn, $_POST['location']);
$summary = mysqli_real_escape_string($conn, $_POST['summary']);
$post_registration_message = mysqli_real_escape_string($conn, $_POST['post-reg-message']);
$in_workshop_message = mysqli_real_escape_string($conn, $_POST['in-workshop-message']);

if($_POST['max-population']=="") $maxPopulation = "NULL";
else $maxPopulation = $_POST['max-population'];

if(isset($_POST['registration-question']))
$registration_questions = mysqli_real_escape_string($conn,json_encode($_POST['registration-question']));
else
$registration_questions = "{}";

  // echo $registration_questions;

  $fmedia = $_POST['fmedia'];
  $fmedia_flag = 1;

  if($fmedia != "unchanged"){

    if($fmedia !="NULL"){
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
          $fmedia = "<img class='workshop-fmedia' src='resources/files/workshops/" . $workshop_id . "/" . $fmedia . "' alt='" . "' />";
        } elseif(in_array($ext, $supported_videos)){
          //supported video
          $fmedia = "<video class='workshop-fmedia' controls>" .
          "<source src='resources/files/workshops/" . $workshop_id . "/" . $fmedia . "' type='video/mp4'>" .
          "Your browser does not support the video tag." .
          "</video>";
        }else{
          //unsupported image/video
        }
      }

      $fmedia = mysqli_real_escape_string($conn, $fmedia);
    }else{
      $fmedia_flag = 0;
    }
  } else{
    $fmedia_flag = -1;
  }
  //updating workshop members
  foreach ($workshop_managers as $value){
    $sql = "SELECT * FROM `users` where username = '" . $value . "'";

  }

  //removing removed members
  $workshop_managers_sql = "(" . implode(",",array_map(function ($v) { return "'" . $v . "'"; },$workshop_managers)) . ")";

  if($no_managers){
    $sql = "DELETE from workshop_managers where workshop_id='" . $workshop_id . "'";
    $request = mysqli_query($conn, $sql);
  }else{
    $sql = "SELECT users.username, users.id FROM `workshop_managers`,`users` where workshop_managers.user_id = users.id and workshop_id=" . $workshop_id . " and users.username NOT IN " . $workshop_managers_sql;

    // echo $sql;

    if($request = mysqli_query($conn, $sql)){
      while ($removed = mysqli_fetch_array($request)){

        // echo "removing " . $removed['username'];

        $sql2 = "DELETE from workshop_managers where workshop_id='" . $workshop_id . "' AND user_id='" . $removed['id'] . "'";
        $request2 = mysqli_query($conn, $sql2);

      }
    }}
    //adding new members

    $sql = "SELECT users.username, users.id FROM `workshop_managers`,`users` where workshop_managers.user_id = users.id and workshop_id=" . $workshop_id . " and users.username IN " . $workshop_managers_sql;

    if($request = mysqli_query($conn, $sql)){
      while ($present = mysqli_fetch_array($request)){
        if (($key = array_search($present['username'], $workshop_managers)) !== false) {
          unset($workshop_managers[$key]);
        }
      }
    }

    $workshop_managers_sql = "(" . implode(",",array_map(function ($v) { return "'" . $v . "'"; },$workshop_managers)) . ")";
    $sql = "SELECT users.username, users.id FROM `users` where users.username IN " . $workshop_managers_sql;

    if($request = mysqli_query($conn, $sql)){
      while ($added = mysqli_fetch_array($request)){

        // echo "adding " . $added['username'];

        $sql2 = "INSERT into workshop_managers (workshop_id, user_id) values('" . $workshop_id . "', '" . $added['id'] . "')";
        $request2 = mysqli_query($conn, $sql2);

      }
    }

    //member updating done

    //updating other fields
    if($fmedia_flag == 0){
      $sql = "UPDATE workshops set registration_fee='$registration_fee', summary = '$summary', post_registration_message = '$post_registration_message', in_workshop_message='$in_workshop_message', about = '" . $about . "', location='" . $location . "', max_population=" . $maxPopulation . ", registration_questions='" . $registration_questions . "', start='" . $startDate . "', end='" . $endDate . "', accepting='" . $accepting_registrations . "', name='" . $workshop_name . "', fmedia=NULL, public='" . $public_visibility . "' where id='" . $_POST['workshop-id'] . "'";
      // $sql = $fmedia_flag;
    }else if($fmedia_flag == -1){
      $sql = "UPDATE workshops set registration_fee='$registration_fee', summary = '$summary', post_registration_message = '$post_registration_message', in_workshop_message='$in_workshop_message', about = '" . $about . "', location='" . $location . "', max_population=" . $maxPopulation . ", registration_questions='" . $registration_questions . "', start='" . $startDate . "', end='" . $endDate . "', accepting='" . $accepting_registrations . "', name='" . $workshop_name . "', public='" . $public_visibility . "' where id='" . $_POST['workshop-id'] . "'";
      // $sql = $fmedia_flag;
    }else if($fmedia_flag == 1){
      $sql = "UPDATE workshops set registration_fee='$registration_fee', summary = '$summary', post_registration_message = '$post_registration_message', in_workshop_message='$in_workshop_message', about = '" . $about . "', location='" . $location . "', max_population=" . $maxPopulation . ", registration_questions='" . $registration_questions . "', start='" . $startDate . "', end='" . $endDate . "', accepting='" . $accepting_registrations . "', name='" . $workshop_name . "', fmedia='" . $fmedia . "', public='" . $public_visibility . "' where id='" . $_POST['workshop-id'] . "'";
      // $sql = $fmedia_flag;
    }

    // echo $sql;

    $request = mysqli_query($conn, $sql);

    if($request){
      echo "success";
    }else{
      echo $sql;
    }

    // $sql = "update ";
    // print_r($tags);
    ?>
