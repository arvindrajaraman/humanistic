<?php
// This is the (private) page to manage the public contacts page

session_start();
// if($_SESSION['login'] != "success"){
//   header('Location: ./login.php');
// }
require_once("../connections/db_connect.php");

if(isset($_GET['username'])){
  $username = $_GET['username'];
  $sql = "SELECT * from users where username = '" . $username . "'";
  $query = mysqli_query($conn, $sql);
  if(mysqli_num_rows($query)==0){
    if(isset($_SESSION['login']) && $_SESSION['login']=="success")
    header('Location: ./community.php');
    else
    header('Location: ../404.php');
  }
  $profile = mysqli_fetch_array($query);
  if(isset($_SESSION['username']) && $_GET['username']==$_SESSION['username'])
  $self = 1;
  else $self = 0;
}else{
  $username = $_SESSION['username'];
  $sql = "SELECT * from users where username = '" . $username . "'";
  $query = mysqli_query($conn, $sql);
  $profile = mysqli_fetch_array($query);
  $self = 1;
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


<title>@<?php echo $profile['username']; ?> on Humanistic</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../resources/css/common.css">
<link rel="stylesheet" type="text/css" href="../resources/css/profile.css">
</head>
<body>

  <?php if(isset($_SESSION['login']) && $_SESSION['login']=="success"){ ?>

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
                <li <?php if(!$self) echo "class='active'"; ?> ><a href="community.php">Community</a></li>
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
              <li <?php if($self) echo "class='active'"; ?>><a href="profile.php"><span class="glyphicon glyphicon glyphicon-user"></span> <?php echo $_SESSION['fname']; ?></a></li>
              <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>

          </div>
        </div>
      </nav>
    </header>

  <?php } else{ ?>
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
              <li><a href="../index.php">Home</a></li>
              <li><a href="../projects.php">Projects</a></li>
              <li><a href="../workshops.php">Workshops</a></li>
              <!-- <li><a href="contact.php">Contact</a></li> -->
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
              <li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            </ul>

          </div>
        </div>
      </nav>
    </header>

  <?php } ?>

  <div class="container profile-container">
    <?php
    $linkdata = array(
      'username' => $profile['username']
    );
    ?>

    <img class="face" alt="<?php echo $profile['fname'] . ' ' . $profile['lname'] . ' profile photo'; ?>" src="https://generative-placeholders.glitch.me/image?cells=10&width=40&height=40" />
    <h3 class="profile-name"><?php echo $profile['fname'] . ' ' . $profile['lname']; ?></h3>
    <a href="./profile.php?<?php echo http_build_query($linkdata);?>">@<?php echo $profile['username']; ?></a><br />
    <span class="profile-level level-<?php echo ucwords($profile['level']); ?>"><?php echo ucwords($profile['level']); ?></span>

    <?php if($profile['co_designer']){ ?>
      <span class="profile-codesigner">Co-Designer</span>
    <?php } ?>

    <br />
    <?php if(isset($_SESSION['login']) && $_SESSION['login']=="success"){ ?>
      <a href="mailto:<?php echo $_SESSION['email'];?>"><span class="message-button"><span class="glyphicon glyphicon glyphicon-envelope"></span> Message</span></a><br />
    <?php } ?>

    <div>
      <!-- <h4 class="brand-font">Whereabouts</h4> -->
      <span class="glyphicon glyphicon-map-marker"></span> <p id="self-whereabouts"><?php echo $profile['whereabouts'];?></p>
    </div>


    <div>
      <h4 class="brand-font">About</h4>
      <p id="self-about"><?php echo $profile['about'];?></p>
    </div>

    <span class="dull">Last seen <?php echo date( 'F j, Y', strtotime( $profile['last_login'] )); ?></span><br />
    <span class="profile-member-since dull">Joined <?php echo date( 'F Y', strtotime( $profile['member_since'] )); ?></span><br /><br />

    <?php if($self){ ?>
      <button onclick="edit()"><span class="glyphicon glyphicon glyphicon-pencil"></span> Edit profile</button>
    <?php } ?>

  </div>

  <?php if($self){ ?>
    <div id="edit-container-block">
      <div id="edit-container">
        <div id="edit-box">
          <div id="user-form">
            <h4>Edit profile</h4>
            <div>
              <form id="self-edit" action="" onsubmit="editProfile(); return false;" method="post">
                About<br /><textarea name="about" id="edit-about"><?php echo $profile['about']; ?></textarea>
                Wherabouts<br /><div id="edit-whereabouts-container"><input onchange="locationReady = 0;" type="text" id="edit-whereabouts" name="whereabouts" value="<?php echo $profile['whereabouts']; ?>" /></div>
                <input type="hidden" id="edit-wa-lat" name="wa_lat" value="<?php echo $profile['wa_lat']; ?>" />
                <input type="hidden" id="edit-wa-lng" name="wa_lng" value="<?php echo $profile['wa_lng']; ?>" /> <br />
                <input type="checkbox" id="edit-co-designer" name="co_designer" value="1" <?php if($profile['co_designer']) echo "checked"; ?> > I am a co-designer<br />

                <br /><button id="edit-save" type="submit" value="Save"/><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
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

  <?php } ?>

  <footer>
  </footer>

  <script>

  var locationReady = 1;

  function edit(){
    document.getElementById("edit-container-block").style.display="block";
    document.getElementById("edit-container").style.opacity=1;
  }

  function minus_edit(){
    document.getElementById("edit-container-block").style.display="none";
    document.getElementById("edit-container").style.opacity=0;
  }

  function editProfile(id){

    if(locationReady == 0){
      window.alert("Please select a location from the suggestions.");
      return;
    }

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
          document.getElementById('self-whereabouts').innerHTML=document.getElementById('edit-whereabouts').value;

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

<script type='text/javascript'>
function loadMapScenario() {
  Microsoft.Maps.loadModule('Microsoft.Maps.AutoSuggest', {
    callback: onLoad,
    errorCallback: onError
  });
  function onLoad() {
    var options = { maxResults: 5 };
    var manager = new Microsoft.Maps.AutosuggestManager(options);
    manager.attachAutosuggest('#edit-whereabouts', '#edit-whereabouts-container', selectedSuggestion);
  }
  function onError(message) {
    document.getElementById('printoutPanel').innerHTML = message;
  }
  function selectedSuggestion(suggestionResult) {
    // document.getElementById('printoutPanel').innerHTML =
    //     'Suggestion: ' + suggestionResult.formattedSuggestion +
    //         '<br> Lat: ' + suggestionResult.location.latitude +
    //         '<br> Lon: ' + suggestionResult.location.longitude;
    document.getElementById('edit-wa-lat').value=suggestionResult.location.latitude;
    document.getElementById('edit-wa-lng').value=suggestionResult.location.longitude;
    locationReady = 1;
  }
}
</script>

<script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=At0rBviB6BLHwcvjbWXW2CeUyndi-NiYPC2ApgMG4OTSy-6LdwqfhNFznP4TClCD&callback=loadMapScenario' async defer></script>

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
