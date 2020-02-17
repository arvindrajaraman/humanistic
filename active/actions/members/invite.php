<?php
session_start();
if($_SESSION['login'] != "success" || ($_SESSION['level'] != "admin" && $_SESSION['level'] != "volunteer")){
  header('Location: ./login.php');
}

if($_SERVER['REQUEST_METHOD']=='POST'){

  require_once("../../../connections/db_connect.php");

  // print_r($_POST);

  if(isset($_POST['invite'])){

    // echo "post invite=".$_POST['invite'];

    $sql = "SELECT * FROM users WHERE email = '" . $_POST['email'] . "'";
    $request = mysqli_query($conn, $sql);
    if(mysqli_num_rows($request)>0 && 0){
      if(strcmp($_POST['invite'], "ajax") !== 0) {$_SESSION[$_POST['category']]['message'] = "User exists";}
      else {echo "User exists";}
    }else{
      $rset_flag = hash('sha256', rand(1,1000));
      // echo $rset_flag;

      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $fname = mysqli_real_escape_string($conn, $_POST['fname']);
      $lname = mysqli_real_escape_string($conn, $_POST['lname']);

      $sql = "INSERT INTO users (fname, lname, email, level, rset_flag, accepted, whereabouts) VALUES ('" . $fname . "','" . $lname . "','" . $email . "','" . $_POST['category'] . "','" . "$rset_flag" . "', '1', 'Amazing Earth')" ;

      // echo $sql;

      $request = mysqli_query($conn, $sql);

      $sql = "SELECT * FROM users WHERE email='" . $_POST['email'] . "'";
      $request = mysqli_query($conn, $sql);
      $newPerson = mysqli_fetch_array($request);

      require_once "../../../resources/mail/PHPMailerAutoload.php";
      ini_set('include_path', 'resources');
      $mail = new PHPMailer;

      try {
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.dreamhost.com';                   // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'no-reply@humanistic.app';                 // SMTP username
        $mail->Password = '';                           // SMTP password
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

        $linkBase = "http://humanistic.app/active/";
        $linkdata = array(
          'id' => $newPerson['id'],
          'flag' => $rset_flag,
          'action' => "create"
        );
        $link = $linkBase . "preset.php?" . http_build_query($linkdata);

        // echo $link;

        $mail->Subject = "Welcome to HCI!";
        $mail->Body = "Hey " . $_POST['fname'] . " " . $_POST['lname'] . "!<br />Humanistic Co-Design Initiative community welcomes you!<br />Click <a href='" . $link . "' />here</a> (" . $link . ") to set your username and password.<br /><br />Warm regards,<br />HCI Community";
        $mail->AltBody = "Hey " . $_POST['fname'] . " " . $_POST['lname'] . "! Humanistic Co-Design Initiative community welcomes you! Use this link (" . $link . ") to set your username and password. Warm regards, HCI Community";

        if($mail->send()){
          if(strcmp($_POST['invite'], "ajax") == 0) {
            echo "success";
          }
          if(strcmp($_POST['invite'], "ajax") !== 0) {$_SESSION[$_POST['category']]['message'] = "Invite sent";}
        }

      } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
      }

    }

    // echo strcmp($_POST['invite'], "ajax");

    if(strcmp($_POST['invite'], "ajax") !== 0) {
    //If not ajax
    header('Location:../../community.php');
    }
  }else{
    echo "get improper / not invite";
  }
}else{
  echo "request type improper";
}
?>
