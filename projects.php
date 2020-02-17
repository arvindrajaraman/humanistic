<?php
// This page is the (public) projects page

session_start();
//connecting to the database for fetching projects
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


<title>Humanistic - Projects</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="./resources/js/masonry.pkgd.min.js"></script>
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>

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


    <div class="form-group has-feedback">
      <input class="search-bar full-width" id="project-search-string" oninput="projectSearch(this.value)" type="text" placeholder="Search projects" />
      <i class="glyphicon glyphicon-search form-control-feedback"></i>
    </div>

  </div>

  <div id="grid-full" class="container-fluid">

    <div id="grid-container" class="container" style="padding:0px 9px 0px 9px !important;">

      <div id="grid-contents" class="grid projects">

        <!-- <div id="projects"> -->
        <?php
        //fetching the projects to show
        $sql = "SELECT * from projects where public=1 ORDER BY `projects`.`created_on` DESC";
        $query = mysqli_query($conn, $sql);

        while($project = mysqli_fetch_array($query)){
          //for each project = $project
          ?>


          <div class="multi-post grid-item project">
            <a class="overlay" href="./project.php?id=<?php echo $project['id']; ?>"></a>

            <div class="mcontent <?php //echo implode(" ",$tags); ?> <?php //echo implode(" ",explode(",",$row['type'])); ?>">

              <div class="post-fmedia"><?php echo $project['fmedia']; ?></div>

              <div class="post-text">

                <a href="./project.php?id=<?php echo $project['id']; ?>">
                  <div class="post-title">

                    <h3><?php echo $project['name']; ?></h3>

                  </div>
                </a>
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

                <div class="post post-content">
                  <?php echo $project['summary']; ?>
                </div>
              </div>
            </div>



          </div>



          <?php if(0){ ?>
            <div class="project well">
              <div class="row">
                <div class="col-sm-3">
                  <!-- project's featured media -->
                  <?php echo $project['fmedia']; ?>
                </div>
                <div class="col-sm-9">
                  <!-- project's name -->
                  <a href="./project.php?id=<?php echo $project['id']; ?>"><h3 class="project-name"><?php echo $project['name']; ?></h3></a>
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
                    <?php

                    $content = $project['summary'];

                    if (strpos($content, '_cont') !== false){
                      $precontent = str_replace('_cont',"",substr($content,0, strpos($content, '_cont')));
                      $postcontent = str_replace('_cont',"",substr($content, strpos($content, '_cont'), strlen($content) - strpos($content, '_cont')));
                      $more=true;
                    }
                    else {
                      $precontent = $content;
                      $more=false;
                      // $link = $link . "#disqus_thread";
                    }

                    echo $precontent;

                    ?>
                  </div>

                  <!-- <?php
                  if($more){?>
                  <a href="./project.php?id=">Learn more</a>
                <?php }
                ?> -->

              </div>
            </div>
          </div>

        <?php } ?>

      <?php } ?>

      <!-- </div> -->
    </div>
  </div>
</div>

<footer>
</footer>

<script>
function projectSearch(string) {
  // Declare variables
  var input, filter, projectsDiv, projects, name, i, txtValue;
  filter = string.toUpperCase();
  projectsDiv = document.getElementsByClassName("projects")[0];
  projects = projectsDiv.getElementsByClassName('project');

  // Loop through all list items, and hide those who don't match the search query
  for (var i = 0; i < projects.length; i++) {
    // name = projects[i].getElementsByClassName("project-name")[0].getElementsByTagName("input")[0];
    // name2 = projects[i].getElementsByTagName("textarea")[0];
    // txtValue = name.value + name2.innerHTML;

    txtValue=projects[i].innerText;
    console.log(txtValue);

    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      projects[i].style.display = "";
    } else {
      projects[i].style.display = "none";
    }
  }

  $grid.masonry('layout');

}

var $grid = $('.grid').masonry({
  // set itemSelector so .grid-sizer is not used in layout
  itemSelector: '.grid-item',
  // use element for option
  columnWidth: '.grid-item',
  percentPosition: true
});

$grid.imagesLoaded().progress( function() {
  $grid.masonry('layout');
});

</script>

</body>
</html>
