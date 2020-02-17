<?php

session_start();
//connecting to the database for fetching projects
require_once("../../../../../connections/db_connect.php");

if(!isset($_POST['workshop_id']))
{
  echo "invalid access";
  header('Location:../registrants.php');
}

$sql = "select * from workshop_managers where workshop_id = '" . $_POST['workshop_id'] . "' and user_id = '" . $_SESSION['id'] . "'";
$workshop_manager = mysqli_num_rows(mysqli_query($conn, $sql))>0;

if(!(isset($_SESSION['login']) && $_SESSION['login'] == "success" && ($_SESSION['level']=="admin" || $workshop_manager))){
  echo "invalid access";
  header('Location:../../../../workshops.php');
}

$sql = "SELECT * FROM workshops WHERE id='" . $_POST['workshop_id'] . "'";
$request = mysqli_query($conn, $sql);
$workshop = mysqli_fetch_array($request);

$sql = "SELECT * FROM workshop_registrants WHERE ";

if($_POST['registration_status']=="accepted"){
  $sql = $sql . "confirmed in ('1')";
}else if($_POST['registration_status']=="rejected"){
  $sql = $sql . "confirmed in ('0') and cancelled in ('1')";
}else if($_POST['registration_status']=="undecided"){
  $sql = $sql . "confirmed in ('0') and cancelled in ('0')";
}else if($_POST['registration_status']=="all"){
  $sql = $sql . "confirmed in ('1','0') and cancelled in ('1','0')";
}

if($_POST['payment_status']=="paid"){
  $sql = $sql . " and paid in ('1')";
}else if($_POST['payment_status']=="unpaid"){
  $sql = $sql . " and paid in ('0')";
}else if($_POST['payment_status']=="both"){
  $sql = $sql . " and paid in ('0','1')";
}

if($_POST['presence_status']=="present"){
  $sql = $sql . " and present in ('1')";
}else if($_POST['presence_status']=="not_present"){
  $sql = $sql . " and present in ('0')";
}else if($_POST['presence_status']=="both"){
  $sql = $sql . " and present in ('0','1')";
}

$request = mysqli_query($conn, $sql);

require_once "../../../../../resources/mail/PHPMailerAutoload.php";
ini_set('include_path', 'resources');
$mail = new PHPMailer;

$subject = $_POST['subject'];
$content = nl2br($_POST['content']);

$counter = 0;

$linkBase = "http://humanistic.app/active/view/";
$linkdata = array(
  'workshop' => $workshop["id"]
);
$link = $linkBase . "workshop.php?" . http_build_query($linkdata);

try {
  $mail->SMTPDebug = 0;                                 // Enable verbose debug output
  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'smtp.dreamhost.com';                   // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = 'no-reply@humanistic.app';                 // SMTP username
  $mail->Password = '4um@n1st!c';                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = 587;
  $mail->isHTML(true);

  //sending to participants
  $mail->SetFrom('no-reply@humanistic.app', 'Humanistic Co-Design Initiative');

  while($registrant = mysqli_fetch_array($request)){
    $mail->addAddress($registrant['email'], $registrant['fname'] . " " . $registrant['lname']);

    $mail->Subject = "[Workshop Notification] " . $workshop['name'];
    $mail->Body = "" . $content . "<br /><hr />This is a notification from organising team for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative.<br />Click <a href='" . $link . "' />here</a> (" . $link . ") to know more about the workshop.<br /><br />Warm regards,<br />HCI Community";
    $mail->AltBody = "" . $content . " --- This is a notification from organising team for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative. Use this link (" . $link . ") to know more about the workshop. Warm regards, HCI Community";

    $mail->send();

    $mail->ClearAllRecipients();

    $counter++;
  }


  //sending to managers

  $sql2 = "SELECT * FROM `users` WHERE id IN (SELECT user_id FROM workshop_managers WHERE workshop_id='" . $workshop['id'] . "')";
  $request2 = mysqli_query($conn, $sql2);

  while($workshop_manager = mysqli_fetch_array($conn, $request2)){

    $mail->addAddress($workshop_manager['email'], $workshop_manager['fname'] . " " . $workshop_manager['lname']);

    $mail->Subject = "[Workshop Notification] " . $workshop['name'];
    $mail->Body = "" . $content . "<br /><hr />This is a notification from organising team for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative.<br />Click <a href='" . $link . "' />here</a> (" . $link . ") to know more about the workshop.<br /><br />Warm regards,<br />HCI Community";
    $mail->AltBody = "" . $content . " --- This is a notification from organising team for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative. Use this link (" . $link . ") to know more about the workshop. Warm regards, HCI Community";

    $mail->send();

    $mail->ClearAllRecipients();

  }

  //sending to records

  $mail->addAddress("records@humanistic.app");

  $mail->Subject = "[Workshop Notification] " . $workshop['name'];
  $mail->Body = "" . $content . "<br /><hr />This is a notification from organising team for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative.<br />Click <a href='" . $link . "' />here</a> (" . $link . ") to know more about the workshop.<br /><br />Warm regards,<br />HCI Community";
  $mail->AltBody = "" . $content . " --- This is a notification from organising team for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative. Use this link (" . $link . ") to know more about the workshop. Warm regards, HCI Community";

  $mail->send();

  $_SESSION['messages']['email-notification'] = "Email successfully sent to ". $counter ." recipient(s).";
  header('Location:../registrants.php?workshop=' . $workshop['id']);

} catch (Exception $e) {
  $_SESSION['messages']['email-notification'] = "Email sending to ". $counter ." recipient(s) failed. Error code: ". $mail->ErrorInfo;
  header('Location:../registrants.php?workshop=' . $workshop['id']);

}

?>
