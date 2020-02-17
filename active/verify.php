<?php
// This is the (public) page to set username and password

// (c) Harshal Gajjar (gotoharshal@gmail.com)
// This code is available under GNU General Public Licence v3

session_start(); //starting session
if($_SESSION['preset_check']!=1){
  header('Location:login.php');
}
require_once("../connections/db_connect.php");

$error = ""; //preset message shown on invalid login attempt

//error_reporting(0);

$sql="UPDATE users SET verified='1', member_since=NOW() WHERE id='" . $_SESSION['preset_id'] . "' AND rset_flag='" . $_SESSION['preset_flag'] . "'";
$request=mysqli_query($conn,$sql);

// echo $sql;

if(!$request){
  $error='emf-aux/verify';
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


<title>Humanistic Core - Welcome</title>
<meta type="robots" content="nofollow">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../resources/css/common.css">

</head>
<body>

  <header>
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand brand" href="#">Humanistic.</a>
        </div>
        <ul class="nav navbar-nav">
          <!-- <li><a href="overview.php">Overview</a></li>
          <li><a href="projects.php">Projects</a></li>
          <li><a href="community.php">Community</a></li>
          <li><a href="workshops.php">Workshops</a></li>
          <li><a href="contact.php">Contact</a></li> -->
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>Sign Up</a></li> -->
          <!-- <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li> -->
        </ul>

      </div>
    </nav>
  </header>

  <div class="container" id="login_container">
    <div id="login_well">
      Hey <?php echo $_SESSION['user']['fname']; ?>,<br />
      <?php if($error==""){?>
        Email verification was successful, click <a href="login.php">here</a> to login.
      <?php }else{
        ?>
        Email verification failed. Error code: <?php echo $error; ?>
        <?php
      }
      ?>
    </div>
  </div>

  <footer>
  </footer>

  <div id="stats">
  </div>
</body>
</html>
