<?php

require_once('./stripe-php-7.14.2/init.php');

ini_set("log_errors", 1);
ini_set("error_log", "./php-error.txt");

//Set your secret key: remember to change this to your live secret key in production
//See your keys here: https://dashboard.stripe.com/account/apikeys

\Stripe\Stripe::setApiKey('sk_test_jylQ2q2NDGeIWMiGhZyM5SEM00yKcReCZO');

// You can find your endpoint's secret in your webhook settings
$endpoint_secret = 'whsec_4MQIkBW8QAmDFV13S0h3G152njO9Gghc';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}

// Handle the checkout.session.completed event
if ($event->type == 'checkout.session.completed') {
  // Fulfill the purchase...
  $payloadObj = json_decode($payload);
  $stripe_session = $payloadObj->data->object;
  $client_reference = json_decode($stripe_session->client_reference_id);

  require_once("../../../connections/db_connect.php");
  $sql = "UPDATE workshop_registrants SET paid='1' WHERE order_id = '" . $client_reference->order_id . "' and id='" . $client_reference->id . "'";

  $request = mysqli_query($conn, $sql);

  if($request){
    //sending email confirmation

    $sql2 = "SELECT * from workshop_registrants WHERE order_id = '" . $client_reference->order_id . "' and id='" . $client_reference->id . "'";
    $request2 = mysqli_query($conn, $sql2);
    $workshop_registration = mysqli_fetch_array($request2);

    $sql2 = "SELECT * from workshops WHERE id='" . $workshop_registration['workshop_id'] . "'";
    $request2 = mysqli_query($conn, $sql2);
    $workshop = mysqli_fetch_array($request2);

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
      $mail->addAddress($workshop_registration['email'], $workshop_registration['fname'] . " " . $workshop_registration['lname']);
      //$mail->addAddress('contact@example.com');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      $mail->addBCC("records@humanistic.app");

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

      $linkBase = "http://humanistic.app/active/view/";
      $linkdata = array(
        'workshop' => $workshop['id']
      );
      $link = $linkBase . "workshop.php?" . http_build_query($linkdata);

      // echo $link;

      $mail->Subject = "Payment received!";
      $mail->Body = "Hey " . $workshop_registration['fname'] . " " . $workshop_registration['lname'] . "! <br />Your registration fee for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative has been received!<br /> Order ID: " . $workshop_registration['order_id'] . "<br /><br />Click <a href='" . $link . "' />here</a> (" . $link . ") to know more about the workshop.<br /><br />Warm regards,<br />HCI Community";
      $mail->AltBody = "Hey " . $workshop_registration['fname'] . " " . $workshop_registration['lname'] . "! Your registration fee for the " . $workshop['name'] . " workshop by Humanistic Co-Design Initiative has been received! Order ID: " . $workshop_registration['order_id'] . ". Click (" . $link . ") to know more about the workshop. Warm regards, HCI Community";

      $mail->send();

    } catch (Exception $e) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }

  }

}

http_response_code(200);

?>
