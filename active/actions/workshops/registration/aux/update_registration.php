<?php
session_start();
if($_SESSION['login'] != "success" || ($_SESSION['level'] != "admin" && $_SESSION['level'] != "volunteer")){
  header('Location: ./login.php');
}

if($_SERVER['REQUEST_METHOD']=='POST'){

  require_once("../../../../../connections/db_connect.php");

  // print_r($_POST);

  if(isset($_POST['accept'])){

    $email = $_POST['registrant-email'];
    $workshop_id = $_POST['workshop-id'];
    $fname = mysqli_real_escape_string($conn,$_POST['fname']);
    $lname = mysqli_real_escape_string($conn,$_POST['lname']);

    $sql = "select * from workshops where id='$workshop_id'";
    $query = mysqli_query($conn,$sql);
    $workshop = mysqli_fetch_array($query);

    $sql = "UPDATE workshop_registrants SET confirmed='1' WHERE email = '$email' AND workshop_id='$workshop_id'";
    $query = mysqli_query($conn,$sql);

    require_once "../../../../../resources/mail/PHPMailerAutoload.php";
    ini_set('include_path', 'resources');
    $mail = new PHPMailer;

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

      //Recipients
      $mail->SetFrom('no-reply@humanistic.app', 'Humanistic Co-Design Initiative');
      $mail->addAddress($email, $fname . " " . $lname);
      //$mail->addAddress('contact@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      $mail->addBCC("records@humanistic.app");

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

      $linkBase = "http://humanistic.app/active/view/";
      $linkdata = array(
        'workshop' => $workshop_id
      );
      $link = $linkBase . "workshop.php?" . http_build_query($linkdata);

      // echo $link;

      $mail->Subject = "You'll need to pack your bag!";
      $mail->Body = "Hey " . $fname . " " . $lname . "! <br />Your registration for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative has been accepted!<br />Click <a href='" . $link . "' />here</a> (" . $link . ") to know more about the workshop.<br /><br />Warm regards,<br />HCI Community";
      $mail->AltBody = "Hey " . $fname . " " . $lname . "! Your registration for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative has been accepted! Click " . $link . " to know more about the workshop. Warm regards, HCI Community";

      $mail->send();
      echo 'success';
    } catch (Exception $e) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }


  }else if(isset($_POST['reject'])){

    $email = $_POST['registrant-email'];
    $workshop_id = $_POST['workshop-id'];
    $fname = mysqli_real_escape_string($conn,$_POST['fname']);
    $lname = mysqli_real_escape_string($conn,$_POST['lname']);

    $sql = "select * from workshops where id='$workshop_id'";
    $query = mysqli_query($conn,$sql);
    $workshop = mysqli_fetch_array($query);

    $sql = "UPDATE workshop_registrants SET confirmed='0', cancelled='1', present='0' WHERE email = '$email' AND workshop_id='$workshop_id'";
    $query = mysqli_query($conn,$sql);

    require_once "../../../../../resources/mail/PHPMailerAutoload.php";
    ini_set('include_path', 'resources');
    $mail = new PHPMailer;

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

      //Recipients
      $mail->SetFrom('no-reply@humanistic.app', 'Humanistic Co-Design Initiative');
      $mail->addAddress($email, $fname . " " . $lname);
      //$mail->addAddress('contact@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      $mail->addBCC("records@humanistic.app");

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

      $linkBase = "http://humanistic.app/active/view/";
      $linkdata = array(
        'workshop' => $workshop_id
      );
      $link = $linkBase . "workshop.php?" . http_build_query($linkdata);
      $linkAllWorkshops = "http://humanistic.app/workshops.php";

      // echo $link;

      $mail->Subject = "Registration couldn't be completed!";
      $mail->Body = "Hey " . $fname . " " . $lname . "! <br />We regret to inform you that because of limited capacity your registration for the <a href='" . $link . "' />" . $workshop['name'] . "</a> (" . $link . ") workshop by Humanistic Co-Design Initiative could not be completed! We will definately get back to you if place becomes available. <br />Till then you can use <a href='" . $linkAllWorkshops . "' />this</a> (" . $linkAllWorkshops . ") page to search for other workshops.<br /><br />Warm regards,<br />HCI Community";
      $mail->AltBody = "Hey " . $fname . " " . $lname . "! We regret to inform you that because of limited capacity your registration for the " . $workshop['name'] . " (" . $link . ") workshop by Humanistic Co-Design Initiative could not be completed! We will definately get back to you if place becomes available. <br />Till then you can use use " . $linkAllWorkshops . " page to search for other workshops.<br /><br />Warm regards,<br />HCI Community";

      $mail->send();
      echo 'success';
    } catch (Exception $e) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }




  }else if(isset($_POST['present'])){

    $email = $_POST['registrant-email'];
    $workshop_id = $_POST['workshop-id'];
    $fname = mysqli_real_escape_string($conn,$_POST['fname']);
    $lname = mysqli_real_escape_string($conn,$_POST['lname']);

    $sql = "select * from workshops where id='$workshop_id'";
    $query = mysqli_query($conn,$sql);
    $workshop = mysqli_fetch_array($query);

    $sql = "UPDATE workshop_registrants SET confirmed='1', present='1' WHERE email = '$email' AND workshop_id='$workshop_id'";
    $query = mysqli_query($conn,$sql);

    require_once "../../../../../resources/mail/PHPMailerAutoload.php";
    ini_set('include_path', 'resources');
    $mail = new PHPMailer;

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

      //Recipients
      $mail->SetFrom('no-reply@humanistic.app', 'Humanistic Co-Design Initiative');
      $mail->addAddress($email, $fname . " " . $lname);
      //$mail->addAddress('contact@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      $mail->addBCC("records@humanistic.app");

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

      $linkBase = "http://humanistic.app/active/view/";
      $linkdata = array(
        'workshop' => $workshop_id
      );
      $link = $linkBase . "workshop.php?" . http_build_query($linkdata);

      $mail->Subject = "Welcome to " . $workshop["name"] . "!";
      $mail->Body = "Hey " . $fname . " " . $lname . "! <br /><a href='" . $link . "' />This</a> (" . $link . ") is the workshop link which would host things like schedule, notices, files, instructions, etc.<br />Hope you have a great time!<br /><br />Warm regards,<br />HCI Community";
      $mail->AltBody = "Hey " . $fname . " " . $lname . "! This " . $link . " is the workshop link which would host things like schedule, notices, files, instructions, etc. Hope you have a great time!<br /><br />Warm regards,<br />HCI Community";

      $mail->send();
      echo 'success';
    } catch (Exception $e) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }

  }else if(isset($_POST['payment'])){

    $email = $_POST['registrant-email'];
    $workshop_id = $_POST['workshop-id'];
    $fname = mysqli_real_escape_string($conn,$_POST['fname']);
    $lname = mysqli_real_escape_string($conn,$_POST['lname']);

    $sql = "select * from workshops where id='$workshop_id'";
    $query = mysqli_query($conn,$sql);
    $workshop = mysqli_fetch_array($query);

    $order_id = "CNTRCASH" . $workshop_id;

    $sql = "UPDATE workshop_registrants SET paid='1', order_id='$order_id' WHERE email = '$email' AND workshop_id='$workshop_id'";
    $query = mysqli_query($conn,$sql);

    require_once "../../../../../resources/mail/PHPMailerAutoload.php";
    ini_set('include_path', 'resources');
    $mail = new PHPMailer;

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

      //Recipients
      $mail->SetFrom('no-reply@humanistic.app', 'Humanistic Co-Design Initiative');
      $mail->addAddress($email, $fname . " " . $lname);
      //$mail->addAddress('contact@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      $mail->addBCC("records@humanistic.app");

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

      $linkBase = "http://humanistic.app/active/view/";
      $linkdata = array(
        'workshop' => $workshop_id
      );
      $link = $linkBase . "workshop.php?" . http_build_query($linkdata);

      // echo $link;

      $mail->Subject = "Payment received!";
      $mail->Body = "Hey " . $fname . " " . $lname . "! <br />Your registration fee for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative has been received!<br /> Order ID: " . $order_id . "<br /><br />Click <a href='" . $link . "' />here</a> (" . $link . ") to know more about the workshop.<br /><br />Warm regards,<br />HCI Community";
      $mail->AltBody = "Hey " . $fname . " " . $lname . "! Your registration fee for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative has been received! Order ID: " . $order_id . ". Click (" . $link . ") to know more about the workshop. Warm regards, HCI Community";

      $mail->send();
      echo 'success';
    } catch (Exception $e) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }


  }

}

?>
