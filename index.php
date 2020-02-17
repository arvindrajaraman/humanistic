<?php
// This is the (public) home page

session_start();
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


<title>Humanistic - Home</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="resources/css/index.css">
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
          <!-- <a class="navbar-brand brand" href="#">Humanistic.</a> -->
        </div>
        <div class="collapse navbar-collapse" id="nav-main">
          <!-- <ul class="nav navbar-nav">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="projects.php">Projects</a></li>
          <li><a href="workshops.php">Workshops</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul> -->

        <ul class="nav navbar-nav navbar-right">
          <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
          <li><a href="./active/login.php"><span class="glyphicon glyphicon-log-in"></span> Enter</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="brand-container-homepage">
      <h1><span class="brand brand-homepage">Humanistic.</span></h1>
      by Humanistic Co-Design Initiative
    </div>

  </div>
</header>

<br />
<div class="container">

  <div class="row">
    <div class="col-sm-8">
      <blockquote>
        Humanistic Co-design is a extension of contemporary design approaches, such as design thinking and human-centered design, that emphasizes the emergent inspiration that comes from the dissolution of the designer-client relationship in favor of a mutual engagement of peers with complementary experiences and expertise.
        <footer>​Dr. Kyle Keane, ERYT-500, YACEP, HcoI-CL<br />
          Lecturer and Research Scientist<br />
          Massachusetts Institute of Technology<br />
        </footer>
      </blockquote>
      <br />
    </div>
    <div class="col-sm-4">
      Humanistic Co-design Initiative is a cooperative of individuals, organizations and institutions working together to increase awareness about how designers, makers, and engineers can apply their skills in collaboration with people who have disabilities through the Humanistic Co-design Process to develop new and innovative assistive technologies.
    </div>
  </div>

  <br />


  <div class="homepage-links">
    <h3>Contribute</h3>
    <a href="./projects.php">
      <div class="tile projects-tile">
        <div class="tile-content projects-tile-content">
          <h4>Towards Projects</h4>
        </div>
      </div>
    </a>

    <a href="./workshops.php">
      <div class="tile workshops-tile">
        <div class="tile-content workshops-tile-content">
          <h4>Attend Workshop</h4>
        </div>
      </div>
    </a>

  </div>

  <br />


  <div class="homepage-links">
    <h3>Learn more</h3>
    <a href="./about.php">
      <div class="tile about-tile">
        <div class="tile-content about-tile-content">
          <h4>About us</h4>
        </div>
      </div>
    </a>

  </div>


  <!-- <p>
  <ul>
  <li>We have curated resources for you to learn more about the  Humanistic Co-design Process.</li>
  <li>Looking to connect? Join us at one of our in-person workshops or online meetups and bring your friends along!</li>
  <li>Looking for tangible design outputs? Check out Student Projects to see what our events are generating.</li>
  <li>Looking to become a certified trainer? Visit our Certification page to find out more.</li>
  <li>If your organization would like to be a part of the initiative or learn about those who are, then checkout our Partners page.</li>
</ul>
</p> -->

</div>
<footer>
</footer>
</body>
</html>
