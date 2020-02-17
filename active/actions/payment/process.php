<?php
session_start();

if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if(!isset($_GET['session_id']) && !isset($_GET['purpose'])) {
  header('Location: ../../login.php');
};

// require_once("../../../connections/db_connect.php");

$registration_id = $_GET['registration_id'];
$workshop_id = $_GET['workshop_id'];

// $sql = "SELECT * FROM workshops where id='$workshop_id'";
// $request = mysqli_query($conn, $sql);
//
// if(mysqli_num_rows($request)==0){
//   echo "Error FOUL-wID".$workshop_id;
//   die();
// }
//
// $workshop = mysqli_fetch_array($request);
//
// //verification of payment
// require_once('./stripe-php-7.14.2/init.php');
//
// \Stripe\Stripe::setApiKey('sk_test_jylQ2q2NDGeIWMiGhZyM5SEM00yKcReCZO');
// $stripe_session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
// $client_reference = json_decode($stripe_session['client_reference_id']);
//
// if($client_reference['id']!=$registration_id){
//   echo "Error FOUL-rID";
//   die();
// }

//verify whether order id has been used before
// $sql = "SELECT * FROM workshop_registrants WHERE order_id = '" . $client_reference['order_id'] . "'";
// $request = mysqli_query($conn, $sql);
// $workshop_registration = mysqli_fetch_array($request);
//
// if(mysqli_num_rows($request)==0){
//   //order id non duplicate
//
//   $sql = "SELECT * from workshop_registrants WHERE id='$registration_id'";
//   $request = mysqli_query($conn, $sql);
//
//   $order_id = $client_reference['order_id'];
//
//   $workshop_registration = mysqli_fetch_array($request);
//
//   header('Location:../../view/workshop.php?workshop='.$workshop_id);
//
// }else{
//   echo "Error FOUL-oID-".$order_id;
// }

header('Location:../../view/workshop.php?workshop='.$workshop_id);
?>
