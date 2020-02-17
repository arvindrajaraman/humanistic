<?php
// This is the (public) contacts page

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


<title>Humanistic - About</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="resources/css/about.css">
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
            <li><a href="workshops.php">Workshops</a></li>
            <li class="active"><a href="#">About</a></li>
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

    <h3>Entities in the news</h3>

    <div class="row less-gap news-row">
      <div class="col-sm-6">
        <div class="tile full-width">
          <div class="tile-content">
            <h4>MISTI Radio</h4>
            <iframe width="100%" height="300" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/589745049&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe>
            Focus on India with MIT-India Managing Director Mala Ghosh, Faculty Leader Dr. Kyle Keane and student participants Pramoda Karnati (BS ’20, CompSci and Engineering), Priyanka Ray Barua (MS ’20, Integrated Design and Management) and Lauren Cooper (BS ’21, Materials Science and Engineering) discuss their participation in a new faculty-led program about assistive technology, workshopping designs for individuals with disabilities by collaborating with individuals with disabilities. For a written transcript of this show, please contact misti-comm@mit.edu.
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">

            <a target="_blank" href="https://g3ict.org/headlines/co-designing-assistive-technologies-in-india">
              <div class="tile full-width">
                <div class="tile-content">
                  <img src="./resources/files/web/about/G3ict_web_banner_map_fnl.gif" alt="G3ict Logo" />
                  <h4>Co-Designing Assistive Technologies in India</h4>
                  <p>
                    MIT students connect with premier Indian institutes, hospitals, and students to collaborate on “humanistic” assistive design.
                  </p>
                </div>
              </div>
            </a>

            <a target="_blank" href="https://mitili.mit.edu/news/qa-kyle-keane-lecturer-and-research-scientist-mits-department-materials-science-and">
              <div class="tile full-width">
                <div class="tile-content">
                  <!-- <img src="./resources/files/web/about/mit_open_learning_logo.png" alt="MIT Open Learning Logo" /> -->
                  <h4>Q&A with Kyle Keane, Lecturer and Research Scientist in MIT’s Department of Materials Science and Engineering</h4>
                  <p>
                    Keane is a creative technologist with a deeply utilitarian commitment to disability advocacy and educational innovation, He has been a lead instructor for 6.811 Principles and Practices of Assistive Technology, 3.016 Computational Methods for Materials Science and Engineering, and 3.024 Electronic, Optical, and Magnetic Properties of Materials.
                  </p>
                </div>
              </div>
            </a>

          </div>
          <div class="col-sm-6">

            <a target="_blank" href="https://news.mit.edu/2019/co-designing-assistive-technologies-india-0325">
              <div class="tile full-width">

                <div class="tile-content">
                  <img src="./resources/files/web/about/mit_news_logo.png" alt="MIT News Logo" />
                  <h4>Co-Designing Assistive Technologies in India</h4>
                  <p>
                    MIT students connect with premier Indian institutes, hospitals, and students to collaborate on “humanistic” assistive design.
                  </p>
                </div>
              </div>
            </a>

            <a target="_blank" href="https://news.mit.edu/2018/bringing-humanistic-education-in-technical-subjects-to-the-world-0628">
              <div class="tile full-width">

                <div class="tile-content">
                  <img src="./resources/files/web/about/mit_news_logo.png" alt="MIT News Logo" />
                  <h4>Bringing humanistic education in technical subjects to the world</h4>
                  <p>
                    Students and staff combine workshopping and OpenCourseWare to demonstrate human-centered pedagogies in the context of modern topics and technologies.
                  </p>
                </div>
              </div>
            </a>

          </div>
        </div>


      </div>
      <div class="col-sm-6">

        <a target="_blank" href="https://jwel.mit.edu/news/upcoming-j-wel-webinar-hyperlocal-co-design-assistive-technologies">
          <div class="tile full-width">
            <div class="tile-content">
              <img src="./resources/files/web/about/logo.png" alt="MIT J-WEL Logo" />
              <h4>Hyperlocal Co-design for Assistive Technologies</h4>Webinar with Kyle Keane on Aug. 7, 2019
              <br /><br />
              <p>
                Hear from Dr. Kyle Keane and Anna Musser about their experiences building a study abroad program with MIT-India that brings MIT undergraduates to developing countries where they co-design assistive technologies by collaborating with local college students, working professionals, and people living with disabilities. This feet-on-the-ground, intercultural experience provides students with a unique opportunity to find highly-impactful engineering projects that align their technical skills and humanistic ideals. One of these projects, funded by J-WEL, will continue to full production and distribution over the next year. We will hear about the itinerary and highlights of the latest trip, first-hand stories from students who participated in the program, ongoing efforts, future plans, and details about the logistics, partnerships, and other resources needed to replicate this model.
              </p>
            </div>
          </div>
        </a>

        <a target="_blank" href="https://openlearning.mit.edu/events/assistive-technology-opening-minds-hands-and-hearts">
          <div class="tile full-width">
            <div class="tile-content">
              <img src="./resources/files/web/about/mit_open_learning_logo.png" alt="MIT Open Learning Logo" />
              <h4>Assistive Technology for Opening Minds, Hands, and Hearts</h4>xTalk on Apr. 16, 2019 with Julie Greenberg, Kyle Keane, Anna Musser, Jaya Narain and Pramoda Karnati
              <br /><br />
              <p>
                Tues. April 16, join us for a panel discussion with MIT educators and students speaking on three activities that engage students in hands-on, real world problem-solving where students collaborate directly with people who have disabilities on engineering and design projects. This panel discussion will be immediately followed by a reception featuring an AT Exploratorium and ATIC showcase.
              </p>
            </div>
          </div>
        </a>

        <a target="_blank" href="https://ocw.mit.edu/resources/res-3-003-learn-to-build-your-own-videogame-with-the-unity-game-engine-and-microsoft-kinect-january-iap-2017/">
          <div class="tile full-width">
            <div class="tile-content">
              <img src="./resources/files/web/about/mit_ocw.png" alt="MIT OCW Logo" />
              <h4>Learn to Build Your Own Videogame with the Unity Game Engine and Microsoft Kinect</h4>
              <p>
                This is a 9-day hands-on workshop by Kyle Keane, Andrew Ringler, Abhinav Gandhi, and Mark Vrablic about designing, building, and publishing simple educational videogames. No previous experience with computer programming or videogame design is required; beginning students will be taught everything they need to know and advanced students will be challenged to learn new skills. Participants will learn about videogame creation using the Unity game engine, collaborative software development using GitHub, gesture handling using the Microsoft Kinect, 3D digital object creation, videogame design, and small team management.
              </p>
              <p>
                This course is offered during the Independent Activities Period (IAP), which is a special 4-week term at MIT that runs from the first week of January until the end of the month.
              </p>
            </div>
          </div>
        </a>


      </div>
    </div>

  </div>

  <footer>
  </footer>

  <script>
  </script>

</body>
</html>
