<?php
// This is the (private) page to manage community members

// NOTE:
// community members' member type = 'member'
// volunteers' member type = 'volunteer'
// admins' member type = 'admin'

// new member types can be created similarly

session_start();
if($_SESSION['login'] != "success"){
  header('Location: ./login.php');
}else{

  if($_SESSION['level']!="admin" && $_SESSION['level']!="volunteer"){
    header('Location: ./login.php');
  }

  //connecting to the database
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


  <title>Humanistic Core - Community</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="../resources/js/masonry.pkgd.min.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../resources/css/common.css">
  <link rel="stylesheet" type="text/css" href="../resources/css/community.css">
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
              <li class="active"><a href="community.php">Community</a></li>
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
            <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>Sign Up</a></li> -->

            <?php
            if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
              ?>
              <li><a href="console.php"><span class="glyphicon glyphicon glyphicon-cog"></span> Console</a></li>
            <?php }?>

            <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['fname']; ?></a></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
          </ul>

        </div>
      </div>
    </nav>
  </header>

  <div id='myMap' style='width: 100vw; height: 50vh;'></div>

  <div class="container-fluid bottom-gap" id="team_container_fluid">

    <div class="container" id="team">
      <h3>Manage Community</h3>
      <div class="row">
        <div class="col-sm-8 bottom-gap group-column">
          <h4>Invite community member</h4>
          <div class="grid-item">
            <div class='team_member'>
              <form action='actions/members/invite.php' method='post'>
                <input name='category' type='hidden' value='member' />
                <span class='input-label'>First Name</span><input name='fname' type='text' value='' placeholder="James" /><br />
                <span class='input-label'>Last Name</span><input name='lname' type='text' value='' placeholder="Biggs" /><br />
                <span class='input-label'>Email</span><input name='email' type='text' value='' placeholder="james@email.com" /><br />
                <input type='submit' name='invite' value='invite'/>
              </form>
              <?php if(isset($_SESSION['member']['message'])) {echo $_SESSION['member']['message']; unset($_SESSION['member']['message']);} ?>
            </div>
          </div>
          <h4>Existing community members</h4>
          <div class="grid">
            <?php
            $sql = "SELECT * FROM `users` where level='member'";
            $request = mysqli_query($conn, $sql);
            $nPeople = mysqli_num_rows($request);
            if($nPeople == 0){
              ?>
              <span class="empty-room">No members!</span>
              <?php
            }
            while($user = mysqli_fetch_array($request)){
              // for each community member = $user
              ?>
              <div class="grid-item">
                <div class='team_member'>
                  <form action='actions/members/modify.php' method='post'>
                    <input name='category' type='hidden' value='member' />
                    <input name='id' type='hidden' value='<?php echo $user['id']; ?>' />
                    <span class='input-label'>First Name</span><input name='fname' type='text' value='<?php echo $user['fname']; ?>' /><br />
                    <span class='input-label'>Last Name</span><input name='lname' type='text' value='<?php echo $user['lname']; ?>' /><br />
                    <span class='input-label'>Username</span><input name='username' type='text' value='<?php echo $user['username']; ?>'/><br />
                    <span class='input-label'><a href='mailto:<?php echo $user['email']; ?>?subject=[HC-Initiative]%20Hey%20there!'>Email</a></span><input name='email' type='text' value='<?php echo $user['email']; ?>'/><br />

                    <input type='submit' name='update' value='update'/>
                    <input type='submit' name='remove' value='remove'/>
                  </form>
                </div>
              </div>

              <?php
            }
            ?>
          </div>
        </div>

        <?php if($_SESSION['level']=="admin"){
          ?>
          <div class="col-sm-2 bottom-gap group-column">
            <h4>Invite volunteer</h4>
            <div class="grid-item">
              <div class='team_member'>
                <form action='actions/members/invite.php' method='post'>
                  <input name='category' type='hidden' value='volunteer' />
                  <span class='input-label'>First Name</span><input name='fname' type='text' value='' placeholder="James" /><br />
                  <span class='input-label'>Last Name</span><input name='lname' type='text' value='' placeholder="Biggs" /><br />
                  <span class='input-label'>Email</span><input name='email' type='text' value='' placeholder="james@email.com" /><br />
                  <input type='submit' name='invite' value='invite'/>
                </form><?php if(isset($_SESSION['volunteer']['message'])) {echo $_SESSION['volunteer']['message']; unset($_SESSION['volunteer']['message']);} ?>
              </div>
            </div>
            <h4>Existing volunteers</h4>
            <?php
            $sql = "SELECT * FROM `users` where level='volunteer'";
            $request = mysqli_query($conn, $sql);
            $nPeople = mysqli_num_rows($request);
            if($nPeople == 0){
              ?>
              <span class="empty-room">No volunteers!</span>
              <?php
            }
            while($user = mysqli_fetch_array($request)){
              // for each volunteer member = $user
              ?>
              <div class="grid-item">
                <div class='team_member'>
                  <form action='actions/members/modify.php' method='post'>
                    <input name='category' type='hidden' value='volunteer' />
                    <input name='id' type='hidden' value='<?php echo $user['id']; ?>' />
                    <span class='input-label'>First Name</span><input name='fname' type='text' value='<?php echo $user['fname']; ?>' /><br />
                    <span class='input-label'>Last Name</span><input name='lname' type='text' value='<?php echo $user['lname']; ?>' /><br />
                    <span class='input-label'>Username</span><input name='username' type='text' value='<?php echo $user['username']; ?>'/><br />
                    <span class='input-label'><a href='mailto:<?php echo $user['email']; ?>?subject=[HC-Initiative]%20Hey%20there!'>Email</a></span><input name='email' type='text' value='<?php echo $user['email']; ?>'/><br />

                    <input type='submit' name='update' value='update'/>
                    <input type='submit' name='remove' value='remove'/>
                  </form>
                </div>
              </div>

              <?php
            }
            ?>
          </div>

          <div class="col-sm-2 bottom-gap group-column">
            <h4>Invite admin</h4>
            <div class="grid-item">
              <div class='team_member'>
                <form action='actions/members/invite.php' method='post'>
                  <input name='category' type='hidden' value='admin' />
                  <span class='input-label'>First Name</span><input name='fname' type='text' value='' placeholder="James" /><br />
                  <span class='input-label'>Last Name</span><input name='lname' type='text' value='' placeholder="Biggs" /><br />
                  <span class='input-label'>Email</span><input name='email' type='text' value='' placeholder="james@email.com" /><br />
                  <input type='submit' name='invite' value='invite'/>
                </form>
                <?php if(isset($_SESSION['admin']['message'])) {echo $_SESSION['admin']['message']; unset($_SESSION['admin']['message']);} ?>
              </div>
            </div>
            <h4>Existing admins</h4>
            <?php
            $sql = "SELECT * FROM `users` where level='admin'";
            $request = mysqli_query($conn, $sql);
            while($user = mysqli_fetch_array($request)){
              // for each admin member = $user
              ?>
              <div class="grid-item">
                <div class='team_member'>
                  <form action='actions/members/modify.php' method='post'>
                    <input name='category' type='hidden' value='admin' />
                    <input name='id' type='hidden' value='<?php echo $user['id']; ?>' />
                    <span class='input-label'>First Name</span><input name='fname' type='text' value='<?php echo $user['fname']; ?>' /><br />
                    <span class='input-label'>Last Name</span><input name='lname' type='text' value='<?php echo $user['lname']; ?>' /><br />
                    <span class='input-label'>Username</span><input name='username' type='text' value='<?php echo $user['username']; ?>'/><br />
                    <span class='input-label'><a href='mailto:<?php echo $user['email']; ?>?subject=[HC-Initiative]%20Hey%20there!'>Email</a></span><input name='email' type='text' value='<?php echo $user['email']; ?>'/><br />

                    <input type='submit' name='update' value='update'/>
                    <input type='submit' name='remove' value='remove'/>
                  </form>
                </div>
              </div>

              <?php
            }
            ?>
          </div>
          <?php
        }
        ?>

      </div>
    </div>
  </div>
</div>


<?php
}
?>

<?php
$sql = "SELECT wa_lat, wa_lng from users";
$query = mysqli_query($conn, $sql);
$locations = Array();
while($coords = mysqli_fetch_array($query)){
  if(!is_null($coords['wa_lat']) && !is_null($coords['wa_lng']))
  array_push($locations, Array(floatval($coords['wa_lat']),floatval($coords['wa_lng'])));
}
?>

<script type='text/javascript'>
function loadMapScenario() {
  var map = new Microsoft.Maps.Map(document.getElementById('myMap'), {
    /* No need to set credentials if already passed in URL */
    center: new Microsoft.Maps.Location(23.252222, 21.003747),
    zoom: 0 });
    Microsoft.Maps.loadModule('Microsoft.Maps.HeatMap', function () {
      // Creating sample Pushpin data within map view
      var mapDiv = map.getRootElement();
      var rawcoords = JSON.parse("<?php echo json_encode($locations); ?>");
      var locations = [];
      for (var i = 0; i < rawcoords.length; i++) {
        var randomLocation = new Microsoft.Maps.Location(rawcoords[i][0],rawcoords[i][1]);
        locations.push(randomLocation);
      }
      var heatMap = new Microsoft.Maps.HeatMapLayer(locations, {
        intensity: 0.65,
        radius: 10,
        colorGradient: {
          '0': 'Black',
          '0.4': 'Green',
          '0.6': 'Yellow',
          '0.8': 'Orange',
          '1': 'Red'
        },
        aggregateLocationWeights: true
      });
      map.layers.insert(heatMap);
    });
    undefined;

  }
  </script>

  <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=At0rBviB6BLHwcvjbWXW2CeUyndi-NiYPC2ApgMG4OTSy-6LdwqfhNFznP4TClCD&callback=loadMapScenario' async defer></script>

  <script>
  var elem = document.querySelector('.grid');
  var msnry = new Masonry( elem, {
    // options
    itemSelector: '.grid-item',
    columnWidth: 175,
  });
</script>

<div id="stats">
</div>
</body>
</html>
