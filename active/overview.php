<?php
// This is the (private) page to show statistics and overview

session_start();
if($_SESSION['login'] != "success"){
  header('Location: ./login.php');
}
require_once("../connections/db_connect.php");
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


<title>Humanistic Core - Overview</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

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
            <li class="active"><a href="#">Overview</a></li>
            <li><a href="projects.php">Projects</a></li>
            <?php
            if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
              ?>
              <li><a href="community.php">Community</a></li>
            <?php }?>
            <li><a href="workshops.php">Workshops</a></li>
            <?php
            if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
              ?>
              <!-- <li><a href="contact.php">Contact</a></li> -->
              <?php
            }
            ?>
          </ul>

          <ul class="nav navbar-nav navbar-right">

          <?php
          if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
            ?>
            <li><a href="console.php"><span class="glyphicon glyphicon glyphicon-cog"></span> Console</a></li>
          <?php }?>
            <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>Sign Up</a></li> -->
            <li><a href="profile.php"><span class="glyphicon glyphicon glyphicon-user"></span> <?php echo $_SESSION['fname']; ?></a></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
          </ul>

        </div>
      </div>
    </nav>
  </header>

  <div class="container">
    <h4>Community</h4>
    <?php
    $sql = "SELECT level as name, COUNT(id) as count FROM users GROUP BY level";
    $query = mysqli_query($conn, $sql);
    while($level = mysqli_fetch_array($query)){
      ?>
      <span style="font-size: 2em;"><?php echo $level['count'];?></span> <?php echo ucwords($level['name']); if($level['count']!=1) echo "s"; ?><br />
      <?php
    }
    ?>

    <h4>Projects</h4>
    <?php
    $sql = "SELECT count(id) as count FROM `projects`";
    $query = mysqli_query($conn, $sql);
    while($project = mysqli_fetch_array($query)){
      ?>
      <span style="font-size: 2em;"><?php echo $project['count'];?></span> <?php echo "Project"; if($project['count']!=1) echo "s"; ?><br />
      <?php
    }
    ?>

    <h4>Workshops</h4>
    <?php
    $sql = "SELECT count(id) as count FROM `workshops`";
    $query = mysqli_query($conn, $sql);
    while($workshop = mysqli_fetch_array($query)){
      ?>
      <span style="font-size: 2em;"><?php echo $workshop['count'];?></span> <?php echo "Workshop"; if($workshop['count']!=1) echo "s"; ?><br />
      <?php
    }
    ?>

  </div>

  <footer>
  </footer>

  <script>

  document.addEventListener('input', function (event) {
    if (event.target.tagName.toLowerCase() !== 'textarea') return;
    autoExpand(event.target);
  }, false);

  var autoExpand = function (field) {

    // Reset field height
    field.style.height = 'inherit';

    // Get the computed styles for the element
    var computed = window.getComputedStyle(field);

    // Calculate the height
    var height = parseInt(computed.getPropertyValue('border-top-width'), 10)
    + parseInt(computed.getPropertyValue('padding-top'), 10)
    + field.scrollHeight
    + parseInt(computed.getPropertyValue('padding-bottom'), 10)
    + parseInt(computed.getPropertyValue('border-bottom-width'), 10);

    field.style.height = height + 'px';

  };
</script>

</body>
</html>
