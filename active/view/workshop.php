<?php
// This page is the (public) projects page

session_start();
//connecting to the database for fetching projects
require_once("../../connections/db_connect.php");

if(!isset($_GET['workshop']))
header('Location:../workshops.php');

$linkBack = htmlspecialchars($_SERVER['PHP_SELF'] . "?workshop=" . $_GET['workshop']);

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-155865977-1"></script>
  <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-155865977-1');
</script>


<title>Humanistic Core - Workshop</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../../resources/css/common.css">
<link rel="stylesheet" type="text/css" href="../../resources/css/register.css">
<link rel="stylesheet" type="text/css" href="../../resources/css/workshops.css">
</head>
<body>
  <?php if(isset($_SESSION['login']) && $_SESSION['login']=="success"){ ?>
    <header>
      <nav class="navbar navbar-default">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-main">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar top-bar"></span>
              <span class="icon-bar middle-bar"></span>
              <span class="icon-bar bottom-bar"></span>
            </button>
            <a class="navbar-brand brand" href="#">Humanistic.</a>
          </div>
          <div class="collapse navbar-collapse" id="nav-main">
            <ul class="nav navbar-nav">
              <li><a href="../overview.php">Overview</a></li>
              <li><a href="../projects.php">Projects</a></li>
              <?php
              if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
                ?>
                <li><a href="../community.php">Community</a></li>
              <?php }?>
              <li class="active"><a href="../workshops.php">Workshops</a></li>
              <?php
              if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
                ?>
                <!-- <li><a href="contact.php">Contact</a></li> -->
                <?php
              }
              ?>
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>Sign Up</a></li> -->
              <li><a href="../profile.php"><span class="glyphicon glyphicon glyphicon-user"></span> <?php echo $_SESSION['fname']; ?></a></li>
              <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>

          </div>
        </div>
      </nav>
    </header>
  <?php } else {?>

    <header>
      <nav class="navbar navbar-default">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-main">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar top-bar"></span>
              <span class="icon-bar middle-bar"></span>
              <span class="icon-bar bottom-bar"></span>
            </button>
            <a class="navbar-brand brand" href="#">Humanistic.</a>
          </div>
          <div class="collapse navbar-collapse" id="nav-main">
            <ul class="nav navbar-nav">
              <li><a href="../../index.php">Home</a></li>
              <li><a href="../../projects.php">Projects</a></li>
              <li class="active"><a href="../../workshops.php">Workshops</a></li>
              <li><a href="../../about.php">About</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
              <li><a href="../login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            </ul>

          </div>
        </div>
      </nav>
    </header>

  <?php } ?>

  <div class="container">

    <button class="back-light" onclick="location.href='<?php if(isset($_SESSION['login']) && $_SESSION['login']=="success") echo'../workshops.php'; else echo '../../workshops.php'; ?>'">View all workshops</button><br /><br />

    <?php

    $sql = "SELECT *, end-now() as time_left FROM `workshops` WHERE id='" . $_GET['workshop'] . "'";

    $query = mysqli_query($conn, $sql);

    $workshop_present = mysqli_num_rows($query)>=1;

    if(!$workshop_present){
      header('Location: ../workshops.php');
    }

    $workshop = mysqli_fetch_array($query);

    ?>

    <div class="workshop well" data-workshop-name="<?php echo $workshop['name']; ?>">

      <?php if(!is_null($workshop['fmedia'])){?>

        <div class="row">
          <div class="col-sm-3">

            <div class="post-fmedia-workshop">
              <?php echo str_replace("resources","../../resources",$workshop['fmedia']); ?>
            </div>

          </div>
          <div class="col-sm-9">
          <?php } ?>

          <h3 class="workshop-name"><?php echo $workshop['name']; ?></h3>

          <span class="glyphicon glyphicon-calendar"></span>
          <?php
          // echo date( 'd F Y (l) g:i:s A', strtotime( $workshop['start'] ));
          echo date( 'd F Y (l)', strtotime( $workshop['start'] ));
          ?>

          to
          <?php
          // echo date( 'd F Y (l) g:i:s A', strtotime( $workshop['end'] ));
          echo date( 'd F Y (l)', strtotime( $workshop['end'] ));
          ?><br />

          <span class="glyphicon glyphicon-map-marker"></span> <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($workshop['location']); ?>" target="_blank"><?php echo $workshop['location']; ?></a><br />

          <span class="glyphicon glyphicon-usd"></span> <?php echo $workshop['registration_fee']; ?> (non refundable)

          <!-- <h4>Managing Team</h4>
          <ul class="workshop-members" id="workshop-<?php echo $workshop['id'];?>-members">
          <?php
          $sql2 = "select fname, lname, users.id, username from workshop_managers, users where workshop_managers.user_id = users.id and workshop_managers.workshop_id = '" . $workshop['id'] . "'";
          $query2 = mysqli_query($conn, $sql2);
          while($manager = mysqli_fetch_array($query2)){?>

          <li class="workshop-manager"><?php echo $manager["fname"] . " " . $manager["lname"]; ?> (<?php echo $manager["username"];?>)</li>

        <?php } ?>
      </ul> -->

      <?php
      if(isset($_SESSION['login']) && $_SESSION['login']=="success"){
        $sql2="select * from workshop_registrants WHERE workshop_id='" . $workshop['id'] . "' and email='" . $_SESSION['email'] . "'";
        $request2=mysqli_query($conn, $sql2);
        $registered_bool=mysqli_num_rows($request2)>0;
      }else{
        $registered_bool=0;
      }
      if($registered_bool){
        echo "<br />";
        //user has registered for this workshop
        $registration = mysqli_fetch_array($request2);
        if($registration['confirmed']){
          ?>
          Registration confirmed
          <?php
        }else if(!$registration['confirmed']){
          ?>
          Registration awaiting confirmation
          <?php
        }
      } else {?>
        <?php if($workshop['accepting']){
          ?>
          <!-- <button onclick="window.location='../actions/workshops/registration/register.php?workshop=<?php echo $workshop['id']; ?>'">Register</button> -->
          <?php
        }
        ?>
        <?php
      }
      ?>
      <br /><br />
      <div class=""><?php echo nl2br($workshop['about']); ?></div>
      <br />


      <?php if(!$registered_bool){
        ?>
        <div id="registration-form">
          <h4>Registration Form</h4>

          <?php


          if(!$workshop['accepting']){
            ?>
            <h4>:(</h4>Unfortunately this workshop isn't accepting registrations right now.
            <?php
          }elseif($workshop['time_left']<0){
            ?>
            <h4>:(</h4>Unfortunately this workshop isn't accepting registrations right now. Travelling backwards in time would have been amazing.
            <?php
          } else{
            //workshop accepting registrants

            if(!isset($_SESSION['login']) || !$_SESSION['login']=="success"){
              $linkBack = htmlspecialchars($_SERVER['PHP_SELF'] . "?workshop=" . $workshop['id']);
              ?>
              <a href="../login.php?next=<?php echo urlencode($linkBack);?>">Login/Signup</a> to continue with the registration.
            <?php }else{
              //person logged in

              if($_SERVER['REQUEST_METHOD']!="POST"){
                //person did not fill the form

                $workshop_id = $_GET['workshop'];

                ?>
                <div class="row">
                  <div class="col-sm-7">
                    <?php
                    $sql3 = "SELECT * FROM `workshop_registrants` where `workshop_id`='" . $workshop_id . "'";
                    $query3 = mysqli_query($conn, $sql3);
                    $registrant_count = mysqli_num_rows($query3);
                    if(!is_null($workshop['max_population']) && $registrant_count>=$workshop['max_population'])
                    {
                      //workshop reached capacity
                      ?>
                      Please note that the workshop capacity has been reached. <br />However, you can add yourself to the waitlist using the form below.<br /><br />
                      <?php
                    }
                    ?>
                    <form method="POST" action="#">
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="inpFirstname">First name</label>
                          <input type="text" value="<?php if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer') echo $_SESSION['fname']; ?>" <?php if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer') echo "disabled"; ?> class="form-control" id="inpFirstname" name="firstname" placeholder="John" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="inpLastname">Last name</label>
                          <input type="text" value="<?php if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer') echo $_SESSION['lname']; ?>" <?php if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer') echo "disabled"; ?> class="form-control" id="inpLastname" name="lastname" placeholder="Dawson" required>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="inputEmail">Email address</label>
                          <input type="email" value="<?php if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer') echo $_SESSION['email']; ?>" <?php if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer') echo "disabled"; ?> class="form-control" id="inputEmail" name="email" aria-describedby="emailHelp" placeholder="john@email.com" required>
                          <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="inputPhone">Phone number</label>
                          <input type="phone" class="form-control" id="inputPhone" name="phone" aria-describedby="phoneHelp" placeholder="+911234567890" value="<?php if(!is_null($_SESSION['phone'])) echo $_SESSION['phone']; ?>" required>
                          <small id="phoneHelp" class="form-text text-muted">Please include the country code</small>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="inputAddress">Address</label>
                          <input type="text" class="form-control" id="inputAddress" name="address" placeholder="1234 Main St" required>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputCity">City</label>
                          <input type="text" class="form-control" name="city" id="inputCity" required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputState">State</label>
                          <input type="text" class="form-control" name="state" id="inputState" required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputCountry">Country</label>
                          <input type="text" class="form-control" name="country" id="inputCountry" required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputZip">Zip</label>
                          <input type="text" class="form-control" name="zip" id="inputZip" required>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-12">

                          <?php

                          $questions = json_decode($workshop['registration_questions']);
                          $questionNumber=0;
                          foreach($questions as $question){
                            $questionNumber++;
                            ?>

                            <div class="form-group">
                              <label for="questionNumber<?php echo $questionNumber;?>"><?php echo $question; ?></label>
                              <textarea required class="form-control" name="registration_questions[]" id="questionNumber<?php echo $questionNumber;?>" required></textarea>
                            </div>

                          <?php } ?>

                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </div>


                      <!-- <div class="form-group form-check">
                      <input type="checkbox" class="form-check-input" id="exampleCheck1">
                      <label class="form-check-label" for="exampleCheck1">I agree to terms and conditions of the workshop.</label>
                    </div> -->

                    <input type="hidden" name="workshop-id" value="<?php echo $workshop['id'];?>" />

                  </form>
                </div>
              </div>
            </div>

            <?php

          }else{
            //person filled the form

            if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer'){
              $firstname = $_SESSION['fname'];
              $lastname = $_SESSION['lname'];
              $email = $_SESSION['email'];
            }else{
              $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
              $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
              $email = mysqli_real_escape_string($conn, $_POST['email']);
            }

            $workshop_id = $_POST["workshop-id"];

            $sql = "select * from workshop_registrants where email='" . $_SESSION['email'] . "' and workshop_id = '" . $workshop_id . "'";
            $query = mysqli_query($conn, $sql);

            $already_registered = mysqli_num_rows($query)>=1;

            if(!$already_registered){

              $phone = mysqli_real_escape_string($conn,$_POST['phone']);
              $address =  mysqli_real_escape_string($conn,join(", ",array($_POST['address'],$_POST['city'],$_POST['state'],$_POST['country'],$_POST['zip'])));
              $questionAnswers = array();

              $QCounter=0;

              foreach(json_decode($workshop['registration_questions']) as $question){
                $key = $question;
                $questionAnswers["$key"] = $_POST['registration_questions'][$QCounter];
                $QCounter++;
              }

              $questionAnswers=mysqli_real_escape_string($conn,json_encode($questionAnswers));

              $sql = 'insert into workshop_registrants (workshop_id,fname,lname,email,phone,address,responses) values ("'.$workshop_id.'","'.$firstname.'","' . $lastname . '","'.$email.'","'.$phone.'","'.$address.'","'.$questionAnswers.'")';
              $query = mysqli_query($conn, $sql);
            }else{
              $query=1;
            }

            if($query){
              //successfully registered
              ?>

              Et voila! Please wait while the team arranges a seat for you in the workshop. You will receive an email once your registration is confirmed.

              <?php
              //email sending pending
            }else{
              //registration unsuccessful
              ?>
              <h3>Registration unsuccessful</h3>Click <a href='<?php echo $_SERVER['REQUEST_URI'];?>'>here</a> to try again.
              <?php
              //email sending pending
            }

          }
          ?>

          <?php
        }

        ?>

        <br /><br />
        <?php
      }?>


      <?php
    }
    ?>

    <?php if($registered_bool && !$registration['confirmed']){ ?>
      <h4>Registration status</h4>
      Please wait while the team arranges a seat for you in the workshop. You will receive an email once your registration is confirmed.
      <br />
    <?php } ?>

    <?php if($registered_bool && $registration['confirmed']){ ?>
      <h4>Post registration</h4>
      <div class=""><?php echo $workshop['post_registration_message']; ?></div>

      <br />

      <?php if($workshop['registration_fee']>0){ ?>

        <h4>Registration fees</h4>

        <div id="payment-area">
          <?php if(!$registration['paid']){
            ?>
            <script src="https://js.stripe.com/v3/"></script>

            <div id="payment-button-container">
              <button onclick="window.location='../actions/payment/initialize.php?purpose=workshop&registration_id=<?php echo $registration['id']; ?>'">Pay</button>
            </div>

            <?php
          }else{
            ?>
            $<?php echo $workshop["registration_fee"]; ?> received<br />
            Order ID: <?php echo $registration["order_id"]; ?>
            <?php
          }
          ?>


        </div><br />

        <?php
      }
      ?>

      <?php
    }
    ?>

    <?php if($registered_bool && $registration['present']){ ?>
      <h4>Namaste</h4>
      <div class=""><?php echo $workshop['in_workshop_message']; ?></div><br />

      <?php
      $dir = "../../resources/files/workshops/" . $workshop["id"] . "/";
      $filesList = scandir($dir);
      if (($key = array_search('.', $filesList)) !== false) {
        unset($filesList[$key]);
      }
      if (($key = array_search('..', $filesList)) !== false) {
        unset($filesList[$key]);
      }
      if (($key = array_search('.DS_Store', $filesList)) !== false) {
        unset($filesList[$key]);
      }

      if(sizeof($filesList)!=0){
        ?>
        <h4>Workshop Files</h4>
        <ul id="workshop-files-list-<?php echo $workshop['id'];?>">

          <?php

          foreach($filesList as $file){
            ?>
            <li><a target="_blank" href="<?php echo $dir.$file; ?>" /><span class="file-name"><?php echo $file; ?></span></a></li>
            <?php
          }
          ?>
        </ul>
        <?php
      }
    }?>

    <?php if(!isset($_SESSION['login'])){
      ?>
      <h4>Post registration</h4>
      If you've already registered then <a href="../login.php?next=<?php echo urlencode($linkBack);?>">login/signup</a> to see this section.<br /><br />

      <?php if(0){ ?>
        <h4>Namaste</h4>
        If you've already registered then <a href="../login.php?next=<?php echo urlencode($linkBack);?>">login/signup</a> to see this section.<br /><br />
        <h4>Workshop Files</h4>
        If you've already registered then <a href="../login.php?next=<?php echo urlencode($linkBack);?>">login/signup</a> to see this section.<br /><br />
      <?php } ?>

    <?php } ?>

    <?php if(!is_null($workshop['fmedia'])){?>
    </div>
  </div>
<?php } ?>

</div>

</div>
</div>

<footer>&nbsp;
</footer>

<script>



</script>

</body>
</html>
