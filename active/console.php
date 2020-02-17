<?php
// This is the (private) page to show statistics and overview

session_start();

if($_SESSION['login'] != "success" || ($_SESSION['level'] != "admin" && $_SESSION['level'] != "volunteer")){
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


<title>Humanistic Core - Console</title>
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
            <li><a href="overview.php">Overview</a></li>
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
            <li class="active"><a href="#"><span class="glyphicon glyphicon glyphicon-cog"></span> Console</a></li>
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

    <div class="tab">
      <button class="tablinks" onclick="openTab(event, 'Meetings')" id="defaultOpen">Meetings</button>
    </div>

    <!-- Tab content -->
    <div id="Meetings" class="tabcontent well">
      <h3>Meetings</h3>
      <?php
        $sql = "SELECT * from meeting_rooms where name='Weekly Meeting'";
        $request = mysqli_query($conn, $sql);
        $meeting_details = mysqli_fetch_array($request);
       ?>
      <form id="weekly-meeting-form" onsubmit="update_meeting_link(); return false;" method="post">
        <span class="input-label">Weekly meeting link</span>
        <input class="full-width" name="weekly-meeting-link" type="text" value="<?php echo $meeting_details['link']; ?>" />
        <button id="edit-save" type="submit" value="Save"/><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
      </form>
    </div>

  </div>

  <footer>
  </footer>

  <script>

  function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
  }

  document.getElementById("defaultOpen").click();

  function update_meeting_link(){
    var data2send = JSON.stringify($("#weekly-meeting-form").serialize());

    data2send=data2send.substring(1, data2send.length-1);

    console.log(data2send);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", './actions/meetings/modify.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {//Call a function when the state changes.
      if(this.readyState == XMLHttpRequest.DONE && this.status == 200) {
        if(xhr.responseText == "success"){
          window.alert("Changes saved");
        }else{
          window.alert(xhr.responseText);
        }
      }
    }

    xhr.send(data2send);

    return false;
  }


</script>

</body>
</html>
