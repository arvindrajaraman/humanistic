<?php
// This page is the (public) projects page
session_start();
//connecting to the database for fetching projects
require_once("../../../../connections/db_connect.php");

if(!isset($_GET['workshop']))
header('Location:../../../workshops.php');

$sql = "select * from workshop_managers where workshop_id = '" . $_GET['workshop'] . "' and user_id = '" . $_SESSION['id'] . "'";
$workshop_manager = mysqli_num_rows(mysqli_query($conn, $sql))>0;

if(!(isset($_SESSION['login']) && $_SESSION['login'] == "success" && ($_SESSION['level']=="admin" || $workshop_manager))){
  header('Location: ../../../view/workshop.php?workshop=' . $_GET['workshop']);
}

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


<title>Humanistic Core - Workshop Registration</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../../../../resources/css/common.css">
<link rel="stylesheet" type="text/css" href="../../../../resources/css/register.css">
</head>
<body>

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

          <?php if(isset($_SESSION['login']) && $_SESSION['login']="success"){
            ?>
            <ul class="nav navbar-nav">
              <li><a href="../../../overview.php">Overview</a></li>
              <li><a href="../../../projects.php">Projects</a></li>
              <?php
              if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
                ?>
                <li><a href="../../../community.php">Community</a></li>
              <?php }?>
              <li class="active"><a href="../../../workshops.php">Workshops</a></li>
              <?php
              if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
                ?>
                <!-- <li><a href="../../../contact.php">Contact</a></li> -->
                <?php
              }
              ?>
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <li><a href="../../../profile.php"><span class="glyphicon glyphicon glyphicon-user"></span> <?php echo $_SESSION['fname']; ?></a></li>
              <li><a href="../../../logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
          <?php } else { ?>

            <ul class="nav navbar-nav">
              <li><a href="../../../../index.php">Home</a></li>
              <li><a href="../../../../projects.php">Projects</a></li>
              <li class="active"><a href="#">Workshops</a></li>
              <!-- <li><a href="contact.php">Contact</a></li> -->
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
              <li><a href="../../../login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            </ul>

          <?php } ?>

        </div>
      </div>
    </nav>
  </header>

  <div class="container">

    <?php if(isset($_SESSION['login']) && $_SESSION['login']="success"){ ?>
      <a href="../../../workshops.php">Back to all workshops</a>
    <?php }else{ ?>
      <a href="../../../../workshops.php">Back to all workshops</a>
    <?php } ?>

    <?php
    $sql = "select *, end-now() as time_left from workshops where id='" . $_GET['workshop'] . "'";
    $query = mysqli_query($conn, $sql);

    $workshop_present = mysqli_num_rows($query)>=1;

    if(!$workshop_present){
      header('Location: ../../../workshops.php');
    }

    $workshop = mysqli_fetch_array($query);

    ?>

    <div class="workshop" data-workshop-name="<?php echo $workshop['name']; ?>">

      <h3 class="workshop-name"><?php echo $workshop['name']; ?></h3>

      <?php
      // echo date( 'd F Y (l) g:i:s A', strtotime( $workshop['start'] ));
      echo date( 'd F Y (l)', strtotime( $workshop['start'] ));
      ?>

      to
      <?php
      // echo date( 'd F Y (l) g:i:s A', strtotime( $workshop['end'] ));
      echo date( 'd F Y (l)', strtotime( $workshop['end'] ));
      ?><br />

      <?php echo $workshop['location']; ?><br /><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($workshop['location']); ?>" target="_blank">View on Google Maps</a><br />

      <!-- <h4>Managing Team</h4>
      <ul class="workshop-members" id="workshop-<?php echo $workshop['id'];?>-members">
      <?php
      $sql2 = "select fname, lname, users.id, username from workshop_managers, users where workshop_managers.user_id = users.id and workshop_managers.workshop_id = '" . $workshop['id'] . "'";
      $query2 = mysqli_query($conn, $sql2);
      while($manager = mysqli_fetch_array($query2)){?>

      <li class="workshop-manager"><?php echo $manager["fname"] . " " . $manager["lname"]; ?> (<?php echo $manager["username"];?>)</li>

    <?php } ?>
  </ul> -->

  <!-- <h4>About</h4>
  <p><?php echo $workshop['about']; ?></p> -->

  <br />

  <div id="form_container">

    <h4>Registration Form</h4>

    <?php
    $workshop_id=$_GET['workshop'];

    if(!$workshop['accepting']){
      ?>
      <h4>:(</h4>Unfortunately this workshop isn't accepting registrations right now.
      <?php
    }elseif($workshop['time_left']<0){
      ?>
      <h4>:(</h4>Unfortunately this workshop isn't accepting registrations right now. Travelling backwards in time would have been amazing.
      <?php
    } else{
      ?>

      <?php if(!isset($_SESSION['login']) || !$_SESSION['login']=="success"){
        $linkBack = htmlspecialchars($_SERVER['PHP_SELF'] . "?workshop=" . $workshop['id']);
        ?>
        <a href="../../../login.php?next=<?php echo urlencode($linkBack);?>">Login/Signup</a> to continue with the registration.
      <?php } else{

        if($_SERVER['REQUEST_METHOD']!="POST"){

          $sql = "select * from workshop_registrants where email='" . $_SESSION['email'] . "' and workshop_id = '" . $workshop_id . "'";
          $query = mysqli_query($conn, $sql);

          $already_registered = mysqli_num_rows($query)>=1;
          $register_others = $_SESSION['level']=="admin" || $_SESSION['level']=="volunteer";

          if($already_registered && !$register_others){
            ?>
            <h3>Appreciate your enthusiasm!</h3>
            <p>We've already considered you, and there is no need of filling the form again. :)<br />
              We will keep you informed via email.</p>

              <?php
            }
            else
            {?>
              <div class="row">
                <div class="col-sm-6">
                  <?php
                  $sql3 = "SELECT * FROM `workshop_registrants` where `workshop_id`='" . $workshop_id . "'";
                  $query3 = mysqli_query($conn, $sql3);
                  $registrant_count = mysqli_num_rows($query3);
                  if(!is_null($workshop['max_population']) && $registrant_count>=$workshop['max_population'])
                  {
                    ?>
                    Please note that the workshop capacity has been reached. <br />However, you can add yourself to the waitlist using the form below.<br /><br />
                    <?php
                  }
                  ?>
                  <form method="POST" action="#">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="inpFirstname">First name</label>
                        <input type="text" value="<?php if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer') echo $_SESSION['fname']; ?>" <?php if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer') echo "disabled"; ?> class="form-control" id="inpFirstname" name="firstname" placeholder="John">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inpLastname">Last name</label>
                        <input type="text" value="<?php if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer') echo $_SESSION['lname']; ?>" <?php if(isset($_SESSION['level']) && $_SESSION['level']!='admin' && $_SESSION['level']!='volunteer') echo "disabled"; ?> class="form-control" id="inpLastname" name="lastname" placeholder="Dawson">
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
                            <textarea class="form-control" name="registration_questions[]" id="questionNumber<?php echo $questionNumber;?>" required></textarea>
                          </div>

                        <?php } ?>
                      </div>
                    </div>


                    <!-- <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">I agree to terms and conditions of the workshop.</label>
                  </div> -->

                  <input type="hidden" name="workshop-id" value="<?php echo $workshop['id'];?>" />

                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
            </div>
          </div>

        <?php }
        ?>

        <?php

      }else{

        if(isset($_SESSION['level']) && ($_SESSION['level']!='admin' AND $_SESSION['level']!='volunteer')){
          $firstname = $_SESSION['fname'];
          $lastname = $_SESSION['lname'];
          $email = $_SESSION['email'];
        }else{
          $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
          $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
          $email = mysqli_real_escape_string($conn, $_POST['email']);
        }

        $sql = "select * from workshop_registrants where email='" . $email . "' and workshop_id = '" . $workshop_id . "'";
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

          // if(isset($_SESSION['login']) && $_SESSION['login']=="success" && $_SESSION['email']==$email){
          // FOR UPDATING CITY BUT REQUIRES IMPLEMENTATION OF BING COORD SEARCH on
          //   // $sql = "update "
          // }

        }else{
          $query=1;
          // echo "you're already registered $email";
        }

        // echo $sql;

        if($query){
          //successfully registered
          unset($_POST);
          ?>

          <h3>Et voila! You've been registered.</h3>We will keep you informed via email.

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

    <?php }
  } ?>

</div>

<footer>&nbsp;
</footer>

<script>
</script>

</body>
</html>
