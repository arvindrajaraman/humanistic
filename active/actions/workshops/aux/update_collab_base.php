<?php
// session_start();
// if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
//   //check API key and continue if valid
// }
//
// if($_SERVER['REQUEST_METHOD']!="POST") die("invalid access method");
//
// require_once("../../../connections/db_connect.php");
// // $about = str_replace('\r','',str_replace(PHP_EOL,'<br />',mysqli_real_escape_string($conn,$_POST['about'])));
//
// $workshop_name = $_POST['workshop-name'];
// $workshop_id = $_POST['workshop-id'];
// $about = mysqli_real_escape_string($conn,$_POST['about']);
// $public_visibility = $_POST['visibility'];
// $accepting_registrations = $_POST['accepting'];
// // $tags = implode(",",$_POST['workshop-tag']);
// $workshop_managers = $_POST['workshop-manager'];
// $startDate = $_POST['startDate'];
// $endDate = $_POST['endDate'];
//
// $fmedia = $_POST['fmedia'];
//
// if($fmedia != "unchanged"){
// if($fmedia == "external") {
//   $fmedia = $_POST['fmedia-external'];
//
//   //removing dimension restrictions
//   $fmedia = preg_replace("([ ]*((width)|(height))[ ]*=[ ]*['\"][0-9]*['\"])","",$fmedia);
// }
// else{
//   $ext = strtolower(pathinfo($fmedia, PATHINFO_EXTENSION));
//
//   $supported_images = Array("jpg","jpeg","png","gif","bmp");
//   $supported_videos = Array("mp4");
//
//   if(in_array($ext, $supported_images)){
//     //supported image
//     $fmedia = "<img class='workshop-fmedia' src='resources/files/workshops/" . $workshop_id . "/" . $fmedia . "' alt='" . "' />";
//   } elseif(in_array($ext, $supported_videos)){
//     //supported video
//     $fmedia = "<video class='workshop-fmedia' controls>" .
//     "<source src='resources/files/workshops/" . $workshop_id . "/" . $fmedia . "' type='video/mp4'>" .
//     "Your browser does not support the video tag." .
//     "</video>";
//   }else{
//     //unsupported image/video
//   }
// }
//
// $fmedia = mysqli_real_escape_string($conn, $fmedia);
// } else{
//   $fmedia = -1;
// }
// //updating workshop members
// foreach ($workshop_managers as $value){
//   $sql = "SELECT * FROM `users` where username = '" . $value . "'";
//
// }
//
// //removing removed members
// $workshop_managers_sql = "(" . implode(",",array_map(function ($v) { return "'" . $v . "'"; },$workshop_managers)) . ")";
//
// $sql = "SELECT users.username, users.id FROM `workshop_managers`,`users` where workshop_managers.user_id = users.id and workshop_id=" . $workshop_id . " and users.username NOT IN " . $workshop_managers_sql;
//
// if($request = mysqli_query($conn, $sql)){
//   while ($removed = mysqli_fetch_array($request)){
//
//     // echo "removing " . $removed['username'];
//
//     $sql2 = "DELETE from workshop_managers where workshop_id='" . $workshop_id . "' AND user_id='" . $removed['id'] . "'";
//     $request2 = mysqli_query($conn, $sql2);
//
//   }
// }
// //adding new members
//
// $sql = "SELECT users.username, users.id FROM `workshop_managers`,`users` where workshop_managers.user_id = users.id and workshop_id=" . $workshop_id . " and users.username IN " . $workshop_managers_sql;
//
// if($request = mysqli_query($conn, $sql)){
//   while ($present = mysqli_fetch_array($request)){
//     if (($key = array_search($present['username'], $workshop_managers)) !== false) {
//       unset($workshop_managers[$key]);
//     }
//   }
// }
//
// $workshop_managers_sql = "(" . implode(",",array_map(function ($v) { return "'" . $v . "'"; },$workshop_managers)) . ")";
// $sql = "SELECT users.username, users.id FROM `users` where users.username IN " . $workshop_managers_sql;
//
// if($request = mysqli_query($conn, $sql)){
//   while ($added = mysqli_fetch_array($request)){
//
//     // echo "adding " . $added['username'];
//
//     $sql2 = "INSERT into workshop_managers (workshop_id, user_id) values('" . $workshop_id . "', '" . $added['id'] . "')";
//     $request2 = mysqli_query($conn, $sql2);
//
//   }
// }
//
// //member updating done
//
// //updating other fields
// if($fmedia != -1)
// $sql = "UPDATE workshops set about = '" . $about . "', start='" . $startDate . "', end='" . $endDate . "', accepting='" . $accepting_registrations . "', name='" . $workshop_name . "', fmedia='" . $fmedia . "', public='" . $public_visibility . "' where id='" . $_POST['workshop-id'] . "'";
// else
// $sql = "UPDATE workshops set about = '" . $about . "', start='" . $startDate . "', end='" . $endDate . "', accepting='" . $accepting_registrations . "', name='" . $workshop_name . "', public='" . $public_visibility . "' where id='" . $_POST['workshop-id'] . "'";
//
// $request = mysqli_query($conn, $sql);
//
// if($request){
//   echo "success";
// }
//
// // $sql = "update ";
// // print_r($tags);
?>
