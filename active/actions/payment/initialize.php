<?php

session_start();

if (!(isset($_SESSION['login']) AND $_SESSION['login'] == "success")){
  //check API key and continue if valid
}

if(!isset($_GET['session_id']) && !isset($_GET['purpose'])) {
  header('Location: ../../login.php');
};
require_once("../../../connections/db_connect.php");

$registration_id = $_GET['registration_id'];
$registration = mysqli_fetch_array(mysqli_query($conn, "select * from workshop_registrants where id='" . $registration_id ."'"));

$workshop = mysqli_fetch_array(mysqli_query($conn, "Select * from workshops where id='" . $registration['workshop_id'] . "'"));


$seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'.'0123456789');
shuffle($seed);
$rand = ''; foreach (array_rand($seed, 15) as $k) $rand .= $seed[$k];

require_once('./stripe-php-7.14.2/init.php');

\Stripe\Stripe::setApiKey('sk_test_jylQ2q2NDGeIWMiGhZyM5SEM00yKcReCZO');
$session = \Stripe\Checkout\Session::create([
  'payment_method_types' => ['card'],
  'client_reference_id' =>
  json_encode(Array(
    'id' => $registration['id'],
    'order_id' => "$rand"
  )),
  'line_items' => [[
    'name' => $workshop['name'] . " - Registration Fee",
    // 'description' => '',
    // 'images' => ['https://example.com/t-shirt.png'],
    'amount' => $workshop['registration_fee']*100,
    'currency' => 'usd',
    'quantity' => 1,
    ]],
    'success_url' => 'https://humanistic.app/active/actions/payment/process.php?purpose=workshop&workshop_id=' . $workshop['id'] . '&registration_id='.  $registration['id'] . '&session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'https://humanistic.app/active/view/workshop.php?workshop=' . $workshop['id'],
  ]);



  $sql = "UPDATE workshop_registrants SET order_id = '" . $rand . "' WHERE id='" . $registration['id'] . "'";
  $request = mysqli_query($conn, $sql);

  $_SESSION['stripe'] = $session;


  ?>

  <html>
  <head>
  <script src="https://js.stripe.com/v3/"></script>
</head><body>

  <script>
  var stripe = Stripe('pk_test_h0kwdxKBH3btlMJU563Y5qjJ00rEXHyEIc');

    stripe.redirectToCheckout({
      // Make the id field from the Checkout Session creation API response
      // available to this file, so you can provide it as parameter here
      // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
      sessionId: '<?php echo $session["id"]; ?>'
    }).then(function (result) {
      // If `redirectToCheckout` fails due to a browser or network
      // error, display the localized error message to your customer
      // using `result.error.message`.
    })

  </script>
</body>
  </html>
