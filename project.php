<?php
// This page is the (public) projects page

session_start();
//connecting to the database for fetching projects
require_once("./connections/db_connect.php");

if(!isset($_GET['id'])){
  header('Location: ./projects.php');
}

//fetching the projects to show
$sql = "SELECT * from projects where id=" . mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query)<1){
  header('Location: ./projects.php');
}

$project = mysqli_fetch_array($query);

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


<title>Humanistic - Project - <?php echo $project['name']; ?></title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="./resources/js/masonry.pkgd.min.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="resources/css/projects.css">
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
            <li class="active"><a href="#">Projects</a></li>
            <li><a href="workshops.php">Workshops</a></li>
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

    <button class="back-light" onclick="location.href='./projects.php'">View all projects</button><br /><br />
    <!-- <a href="./projects.php"><span class="back-light">View all projects</span></a> -->

  </div>

  <div id="grid-full" class="container-fluid">
    <div id="grid-container" class="container" style="padding:0px 9px 0px 9px !important;">
      <div id="grid-contents" class="grid projects">

        <div class="single-post grid-item project">

          <div class="mcontent <?php //echo implode(" ",$tags); ?> <?php //echo implode(" ",explode(",",$row['type'])); ?>">
            <div class="single-post-fmedia post-fmedia"><?php echo $project['fmedia']; ?></div>
            <div class="post-text">


              <div class="post-title">

                <h3><?php echo $project['name']; ?></h3>

              </div>
              <!-- <div class="post-author">by <?php //echo $row['author']; ?></div> -->

              <?php
              $tags = array_filter(explode(",",$project['tags']));
              foreach ($tags as $tag){
                ?>
                <div class="label-dark"><?php echo $tag; ?></div>
                <?php
              }
              if(sizeof($tags)>1){
                echo "<br />";
              }
              ?>
              <div class="post-time"><?php echo $project['stage']; ?></div>

              <h5>Team</h5>
              <ul class="project-members" >

                <?php
                $sql2 = "select fname, lname, users.id, username from project_members, users where project_members.user_id = users.id and project_members.project_id = " . $project['id'];
                $query2 = mysqli_query($conn, $sql2);
                while($member = mysqli_fetch_array($query2)){
                  // for each member = $member
                  $linkdata = array(
                    'username' => $member['username']
                  );
                  ?>

                  <li class="project-member"><a href="./active/profile.php?<?php echo http_build_query($linkdata);?>"><?php echo $member["fname"] . " " . $member["lname"]; ?></a></li>

                <?php } ?>

              </ul>

              <h5>Summary</h5>
              <div class="post post-content">
                <?php echo $project['summary']; ?>
              </div>
              <br />
              <h5>About</h5>
              <div class="post post-content">
                <?php echo nl2br($project['about']); ?>
              </div>

            </div>
          </div>



        </div>
      </div>
    </div>
  </div>



  <?php if(0){ ?>
    <div id="projects">

      <div class="project well">
        <div class="row">
          <div class="col-sm-3">
            <!-- project's featured media -->
            <?php echo $project['fmedia']; ?>
          </div>
          <div class="col-sm-9">
            <!-- project's name -->
            <h3 class="project-name"><?php echo $project['name']; ?></h3>
            <!-- project's stage -->
            Stage: <span class="project-stage"><?php echo $project['stage'];?></span><br />
            <!-- project's tags -->
            Tags
            <ul class="project-tags">
              <?php
              $tags = array_filter(explode(",",$project['tags']));
              foreach($tags as $tag){
                echo "<li>" . $tag . "</li>";
              }
              ?>
            </ul>
            <!-- project's members -->
            Team members
            <ul class="project-members" >

              <?php
              $sql2 = "select fname, lname, users.id, username from project_members, users where project_members.user_id = users.id and project_members.project_id = " . $project['id'];
              $query2 = mysqli_query($conn, $sql2);
              while($member = mysqli_fetch_array($query2)){
                // for each member = $member
                $linkdata = array(
                  'username' => $member['username']
                );
                ?>

                <li class="project-member"><a href="./active/profile.php?<?php echo http_build_query($linkdata);?>"><?php echo $member["fname"] . " " . $member["lname"]; ?></a></li>

              <?php } ?>

            </ul>

            <!-- project's about -->
            <div class="project-about">
              <?php echo $project['about']; ?>
            </div>

          </div>
        </div>
      </div>

    </div>
  <?php } ?>
  <!-- </div> -->

  <footer>
  </footer>

  <script>
  $('.grid').masonry({
    // set itemSelector so .grid-sizer is not used in layout
    itemSelector: '.grid-item',
    // use element for option
    columnWidth: '.grid-item',
    percentPosition: true
  });
  </script>

</body>
</html>
