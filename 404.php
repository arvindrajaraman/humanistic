Error 404: Page not found
<?php
// This is the (private) page to manage the public contacts page

die();

session_start();
require_once("./connections/db_connect.php");

$username = $_GET['username'];

$sql = "SELECT * FROM users WHERE username='" . $username . "'";
$request = mysqli_query($conn,$sql);
$user = mysqli_fetch_array($request);

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


<title>Humanistic Core - Profile</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../resources/css/index.css">
<link rel="stylesheet" type="text/css" href="../resources/css/common.css">
<link rel="stylesheet" type="text/css" href="../resources/css/profile.css">
</head>
<body>

  <header>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand brand" href="#">Humanistic.</a>
        </div>
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
            <li><a href="contact.php">Contact</a></li>
            <?php
          }
          ?>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>Sign Up</a></li> -->
          <li class="active"><a href="profile.php"><span class="glyphicon glyphicon glyphicon-user"></span> <?php echo $_SESSION['fname']; ?></a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>

      </div>
    </nav>
  </header>

  <div class="container">
    <?php
    $sql = "SELECT * from users WHERE username='" . $_SESSION['username'] . "'";
    $query = mysqli_query($conn, $sql);
    $profile = mysqli_fetch_array($query);
    ?>
    <h3><?php echo $profile['fname'] . ' ' . $profile['lname']; ?></h3>
    @<?php echo $profile['username']; ?><br />
    <?php echo ucwords($profile['level']); ?><br />
    Joined <?php echo date( 'F Y', strtotime( $profile['member_since'] )); ?><br />

    <a href="mailto:<?php echo $_SESSION['email'];?>"><span class="glyphicon glyphicon glyphicon-envelope"></span> Message</a>
    <h4>About</h4>
    <p id="self-about"><?php echo $profile['about'];?></p>

    <button onclick="edit()"><span class="glyphicon glyphicon glyphicon-pencil"></span> Edit profile</button>

  </div>

  <div id="edit-container-block">
    <div id="edit-container">
      <div id="edit-box">
        <div id="user-form">
          <h4>Edit profile</h4>
          <div>
            <form id="self-edit" action="" onsubmit="editProfile(); return false;" method="post">
              About<br /><textarea name="about" id="edit-about"><?php echo $profile['about']; ?></textarea>
              <button id="edit-save" type="submit" value="Save"/><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
            </form><br/>
            <span id="edit-message">
              <?php
              if(isset($msg)) echo $msg;
              ?>
            </span>
          </div>
          <button onclick="minus_edit()" style="position:absolute;top:10px;right:15px;color:#FF5A5D;cursor: pointer;"><span style="">✕</span></button>
        </div>
      </div>
    </div>
  </div>

  <footer>
  </footer>

  <script>
  function edit(){
    document.getElementById("edit-container-block").style.display="block";
    document.getElementById("edit-container").style.opacity=1;
  }

  function minus_edit(){
    document.getElementById("edit-container-block").style.display="none";
    document.getElementById("edit-container").style.opacity=0;
  }

  function editProfile(id){

    var form = document.getElementById("self-edit");
    var elements = form.elements;
    for (var i = 0, len = elements.length; i < len; ++i) {
      elements[i].readOnly = true;
    }

    //everything (in the form) seems okay
    var data2send = JSON.stringify($("#self-edit").serialize());

    data2send=data2send.substring(1, data2send.length-1);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", './actions/profile/modify.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {//Call a function when the state changes.
      if(this.readyState == XMLHttpRequest.DONE && this.status == 200) {
        if(xhr.responseText == "success"){
          window.alert("Changes saved");

          document.getElementById('self-about').innerHTML=document.getElementById('edit-about').value;

          var form = document.getElementById("self-edit");
          var elements = form.elements;
          for (var i = 0, len = elements.length; i < len; ++i) {
            elements[i].readOnly = false;
          }

        }else{
          window.alert(xhr.responseText);

          var form = document.getElementById("self-edit");
          var elements = form.elements;
          for (var i = 0, len = elements.length; i < len; ++i) {
            elements[i].readOnly = true;
          }

        }
      }
    }

    xhr.send(data2send);

    return false;
  }

</script>

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
