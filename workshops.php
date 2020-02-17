<?php
// PAGE UNFINISHED
// This page is the (public) workshops page

session_start();
require_once("./connections/db_connect.php");
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


<title>Humanistic - Workshops</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="resources/css/workshops.css">
<link rel="stylesheet" type="text/css" href="resources/css/common.css">
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
            <li><a href="index.php">Home</a></li>
            <li><a href="projects.php">Projects</a></li>
            <li class="active"><a href="#">Workshops</a></li>
            <li><a href="about.php">About</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
            <li><a href="./active/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          </ul>
        </div>

      </div>
    </nav>
  </header>

  <div class="container">

    <div class="form-group has-feedback">
      <input class="search-bar full-width" id="workshop-search-string" oninput="workshopSearch(this.value)" type="text" placeholder="Search workshops" />
      <i class="glyphicon glyphicon-search form-control-feedback"></i>
    </div>

    <div id="workshops">



      <?php

      $sql = "SELECT * FROM `workshops` WHERE public=1 ORDER BY `workshops`.`start` DESC";

      $query = mysqli_query($conn, $sql);

      while($workshop = mysqli_fetch_array($query)){

        // $sql3 = "SELECT * from users where users.id = " . $workshop['creator_id'];
        // $query3 = mysqli_query($conn, $sql3);
        // $creator = mysqli_fetch_array($query3);

        ?>

        <div class="workshop well" data-workshop-name="<?php echo $workshop['name']; ?>">

          <?php if(!is_null($workshop['fmedia'])){?>

            <div class="row">
              <div class="col-sm-3">

                <div class="post-fmedia-workshop">
                  <?php echo $workshop['fmedia']; ?>
                </div>

              </div>
              <div class="col-sm-9">
              <?php } ?>

              <a href="./active/view/workshop.php?workshop=<?php echo $workshop['id']; ?>"><h3 class="workshop-name"><?php echo $workshop['name']; ?></h3></a>

              <span class="glyphicon glyphicon-calendar"></span>
              <?php
              echo date( 'd F Y (l)', strtotime( $workshop['start'] ));
              ?>
              to
              <?php
              echo date( 'd F Y (l)', strtotime( $workshop['end'] ));
              ?><br />

              <span class="glyphicon glyphicon-map-marker"></span> <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($workshop['location']); ?>" target="_blank"><?php echo $workshop['location']; ?></a><br />
              <span class="glyphicon glyphicon-usd"></span> <?php echo $workshop['registration_fee']; ?> (non refundable)<br />

              <?php if($workshop['accepting']){
                ?>
                <button onclick="window.location='./active/view/workshop.php?workshop=<?php echo $workshop['id']; ?>#registration-form'">Register</button><br />
                <?php
              }
              ?>

              <!-- <h4>Tags</h4>
              <ul class="project-tags" id="project-<?php echo $workshop['id'];?>-tags">
              <?php
              // $sql2 = "select tags from projects where id = " . $workshop['id'];
              // $query2 = mysqli_query($conn, $sql2);
              // $result2 = mysqli_fetch_array($query2)["tags"];
              //
              // $tags = array_filter(explode(",",$result2));
              //
              // foreach ($tags as $tag){?>
              //
              //   <li class="project-tag"><?php echo $tag; ?></li>

              <?php //} ?>
            </ul> -->

            <!-- <div class="project-about">
            <?php echo $workshop['about']; ?>
          </div> -->

          <!-- <h4>About</h4> --><br />
          <?php echo $workshop['summary']; ?>

          <?php if(!is_null($workshop['fmedia'])){?>

          </div>
        </div>
      <?php } ?>


    </div>

  <?php } ?>

</div>
</div>

<footer>
</footer>

<script>
function workshopSearch(string) {
  // Declare variables
  var input, filter, workshopsDiv, workshops, name, i, txtValue;
  filter = string.toUpperCase();
  workshopsDiv = document.getElementById("workshops");
  workshops = workshopsDiv.getElementsByClassName('workshop');

  // Loop through all list items, and hide those who don't match the search query
  for (var i = 0; i < workshops.length; i++) {
    // name = workshops[i].getElementsByClassName("workshop-name")[0].getElementsByTagName("input")[0];
    // name2 = workshops[i].getElementsByTagName("textarea")[0];
    // txtValue = name.value + name2.innerHTML;

    txtValue=workshops[i].innerText;

    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      workshops[i].style.display = "";
    } else {
      workshops[i].style.display = "none";
    }
  }
}
</script>

</body>
</html>
