<?php
// This is the (public) page to set username and password

// (c) Harshal Gajjar (gotoharshal@gmail.com)
// This code is available under GNU General Public Licence v3

session_start(); //starting session
if($_SESSION['preset_check']!=1){
  header('Location:login.php');
}

$error = ""; //preset message shown on invalid login attempt

//error_reporting(0);

if(isset($_POST['reset_submit'])){ //if submit button clicked

  $password1=hash('sha256', $_POST['password1']); //removing sql injection attempt
  $password2=hash('sha256', $_POST['password2']); //hashing password

  $username=$_POST['username'];

  require_once("../connections/db_connect.php");

  $error_count=0;

  if(strlen($_POST['password1'])<8){
    $error = "Password length needs to be greater than 8";
    $error_count++;
  }
  if($password1!=$password2){
    $error = "Repeated password needs to match";
    $error_count++;
  }
  if(strlen($_POST['username'])<5){
    $error = "Username needs atleast 5 characters";
    $error_count++;
  }
  if(strstr($username,' ')){
    $error = "Username can not contain a blankspace";
    $error_count++;
  }
  if(preg_match("/DROP/i",$username) OR preg_match("/DELETE/i",$username)){
    $error = "Invalid username";
    $error_count++;
  }
  if(strpos($username, '%') !== false){
    $error = "Invalid username";
    $error_count++;
  }
  if(strpos($username, '\'') !== false){
    $error = "Invalid username";
    $error_count++;
  }
  if(strpos($username, '\"') !== false){
    $error = "Invalid username";
    $error_count++;
  }
  if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬\-\"]/', $username))
    {
      $error = "Invalid username";
      $error_count++;
    }

    if($error_count==0){

      $sql = "SELECT * from users WHERE username='" . $username . "'";
      $request=mysqli_query($conn,$sql);

      $valid = mysqli_num_rows($request)==0;

      if($valid){
        //username unused
        if($_SESSION['db_connection_status']==0){
          //database connection has failed
          $error = "Database connection failed";
        } else{
          //connection to database successful
          $rset_flag = hash('sha256', rand(1,1000));

          $sql="UPDATE users SET password='" . $password1 . "', username='" . $username . "', verified='1', member_since=NOW() WHERE id='" . $_SESSION['preset_id'] . "' AND rset_flag='" . $_SESSION['preset_flag'] . "'"; //sql to find user with entered username and password
          $request=mysqli_query($conn,$sql);

          $sql="update users SET rset_flag='" . $rset_flag . "' WHERE id='" . $_SESSION['preset_id'] . "'";
          $request=mysqli_query($conn,$sql);

          header('Location:login.php');
        }
      }else{
        $error="Username unavailable";
      }
    }

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
      Hey <?php echo $_SESSION['user']['fname']; ?>,<br />Finish setting your account by choosing a username and setting a password<br /><br />
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        Username<br /><input type="text" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>"><br />
        New password<br /><input type="password" name="password1"><br />
        Repeat password<br /><input type="password" name="password2"><br /><br/>
        <input type="submit" name="reset_submit" value="Submit">
      </form>
      <span class="error"><?php echo "<br/>" . $error ?></span>
    </div>
  </div>

  <footer>
  </footer>

  <div id="stats">
  </div>
</body>
</html>
