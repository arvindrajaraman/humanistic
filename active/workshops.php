<?php
// This is the (private) page to manage the public workshops page

session_start();
if($_SESSION['login'] != "success"){
  header('Location: ./login.php');
}
if($_SESSION['level']!="admin" && $_SESSION['level']!="volunteer"){
  // header('Location: ./login.php');
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


<title>Humanistic Core - Workshops</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../resources/css/common.css">
<link rel="stylesheet" type="text/css" href="../resources/css/workshops.css">
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
            <li class="active"><a href="workshops.php">Workshops</a></li>
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
                      <li><a href="console.php"><span class="glyphicon glyphicon-cog"></span> Console</a></li>
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

    <input class="full-width search-bar" id="workshop-search-string" oninput="workshopSearch(this.value)" type="text" placeholder="Search workshops" /><br /><br />

    <?php
    if($_SESSION['level']=="admin"){
      ?>
      <button class="new-light" onclick="document.getElementById('new-workshop').style.display='inline-block'; this.style.display='none'; ">New Workshop</button>
      <div id="new-workshop">
        <form action="./actions/workshops/create.php" method="POST">
          <span class="input-label">Workshop Name</span><input style="padding: 5px;" type="text" name="workshop-name" value="" />
          <input class="new-submit" type="submit" value="Create" />
        </form>
      </div><br /><br />
    <?php } ?>

    <?php if(isset($_SESSION['errors']['workshop-creation'])) {echo $_SESSION['errors']['workshop-creation']; unset($_SESSION['errors']['workshop-creation']);} ?>

    <div id="workshops">
      <?php

      $n_workshops_managing = 0;

      if($_SESSION['level']=="admin"){
        $sql = "SELECT * FROM `workshops` ORDER BY `workshops`.`start` DESC";
      }else if($_SESSION['level']=="volunteer"){
        //page not visible for non admins and non volunteers, nothing done for this though
        $sql="SELECT * FROM `workshops` WHERE (workshops.id IN (SELECT workshop_managers.workshop_id FROM `workshop_managers` WHERE workshop_managers.user_id=" . $_SESSION['id'] . ")) ORDER BY `workshops`.`start` DESC";
      }else if ($_SESSION['level']=="member"){
        $sql = "SELECT * FROM `workshops` WHERE public='1' ORDER BY `workshops`.`start` DESC";
      }

      $query = mysqli_query($conn, $sql);

      $n_workshops_managing = mysqli_num_rows($query);

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
                  <?php echo str_replace("resources","../resources",$workshop['fmedia']); ?>
                </div>

              </div>
              <div class="col-sm-9">
              <?php } ?>


              <a href="./view/workshop.php?workshop=<?php echo $workshop['id']; ?>"><h3 class="workshop-name"><?php echo $workshop['name']; ?></h3></a>
              <!-- Created on <?php echo date('m/d/Y h:i:s a', strtotime($workshop['created_on'])); ?> by <?php echo $creator['fname'] . " " . $creator['lname'] . " (" . $creator['username'] . ")"; ?><br /> -->

              <span class="glyphicon glyphicon-calendar"></span>
              <?php
              // echo date( 'd F Y (l) g:i:s A', strtotime( $workshop['start'] ));
              echo date( 'd F Y (l)', strtotime( $workshop['start'] ));
              ?>

              to
              <?php
              // echo date( 'd F Y (l) g:i:s A', strtotime( $workshop['end'] ));
              echo date( 'd F Y (l)', strtotime( $workshop['end'] ));
              ?><br />

              <span class="glyphicon glyphicon-map-marker"></span> <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($workshop['location']); ?>" target="_blank"><?php echo $workshop['location']; ?></a><br />
              <span class="glyphicon glyphicon-usd"></span> <?php echo $workshop['registration_fee']; ?> (non refundable)<br />

              <?php
              $sql2="select * from workshop_registrants WHERE workshop_id='" . $workshop['id'] . "' and email='" . $_SESSION['email'] . "'";
              $request2=mysqli_query($conn, $sql2);
              $registered_bool=mysqli_num_rows($request2)>0;
              if($registered_bool){
                //user has registered for this workshop
                $registration = mysqli_fetch_array($request2);
                if($registration['confirmed']){
                  ?>
                  Registration confirmed<br />
                  <?php
                }else if(!$registration['confirmed']){
                  ?>
                  Registration awaiting confirmation<br />
                  <?php
                }
              }
              ?>

              <?php if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){ ?>
                <button onclick="window.location='workshop.php?id=<?php echo $workshop['id']; ?>'" >Edit Workshop</button>
              <?php } ?>

              <?php //if(($workshop['accepting'] && !$registered_bool) || ($_SESSION['level']=="admin" && $_SESSION['level']=="volunteer")){
                if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer" || (!$registered_bool && $workshop['accepting'])){
                  ?>
                  <button onclick="window.location='./actions/workshops/registration/register.php?workshop=<?php echo $workshop['id']; ?>'">Register</button>
                  <?php
                }else if(($registered_bool && !$registration['paid'])){ ?>
                  <!-- <button onclick="window.location='./actions/workshops/registration/register.php?workshop=<?php echo $workshop['id']; ?>'">Pay Fee</button> -->

                <?php }
                ?>

                <?php if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){ ?>
                  <button onclick="window.location='./actions/workshops/registration/registrants.php?workshop=<?php echo $workshop['id']; ?>'">Registrations</button>
                <?php }?>

                <br />

                <?php
                if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
                  ?>
                  <h4>Public Visibility</h4>
                  <?php
                  if($workshop['public']) {
                    echo "Visible";
                  }else{
                    echo "Not visible";
                  }
                  ?>

                  <h4>Accepting Registrations</h4>
                  <?php
                  if($workshop['accepting']) {
                    echo "Yes";
                  }else{
                    echo "No";
                  }
                  ?>

                  <?php
                }
                ?>

                <?php if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){ ?>
                  <h4>Managing Team</h4>
                  <ul class="workshop-members" id="workshop-<?php echo $workshop['id'];?>-members">
                    <?php
                    $sql2 = "select fname, lname, users.id, username from workshop_managers, users where workshop_managers.user_id = users.id and workshop_managers.workshop_id = '" . $workshop['id'] . "'";
                    $query2 = mysqli_query($conn, $sql2);
                    while($manager = mysqli_fetch_array($query2)){
                      $linkdata = array(
                        'username' => $manager['username']
                      );
                      ?>

                      <li class="workshop-manager"><a target="_blank" href="./profile.php?<?php echo http_build_query($linkdata);?>"><?php echo $manager["fname"] . " " . $manager["lname"]; ?> (@<?php echo $manager["username"];?>)</a></li>

                    <?php } ?>
                  </ul>
                <?php } ?>

                <!-- <h4>Tags</h4>
                <ul class="workshop-tags" id="workshop-<?php echo $workshop['id'];?>-tags">
                <?php
                // $sql2 = "select tags from workshops where id = " . $workshop['id'];
                // $query2 = mysqli_query($conn, $sql2);
                // $result2 = mysqli_fetch_array($query2)["tags"];
                //
                // $tags = array_filter(explode(",",$result2));
                //
                // foreach ($tags as $tag){?>
                //
                //   <li class="workshop-tag"><?php echo $tag; ?></li>

                <?php //} ?>
              </ul> -->

              <!-- <div class="workshop-about">
              <?php echo $workshop['about']; ?>
            </div> -->

            <!-- <h4>About</h4> -->
            <?php echo $workshop['summary']; ?>

            <?php if(0 && ($registered_bool || $_SESSION['level']=="admin" || $_SESSION['level']=="volunteer")){?>
              <!-- <div class="workshop-files-<?php echo $workshop['id']; ?>">
              <h4>Workshop Files</h4>
              <ul id="workshop-files-list-<?php echo $workshop['id'];?>">

              <?php
              $dir = "../resources/files/workshops/" . $workshop["id"] . "/";
              $filesList = scandir($dir);
              if (($key = array_search('.', $filesList)) !== false) {
              unset($filesList[$key]);
            }
            if (($key = array_search('..', $filesList)) !== false) {
            unset($filesList[$key]);
          }
          if (($key = array_search('.DS_Store', $filesList)) !== false) {
          unset($filesList[$key]);
        }
        foreach($filesList as $file){
        ?>
        <li><a href="<?php echo $dir.$file; ?>" /><span class="file-name"><?php echo $file; ?></span></a></li>
        <?php
      }
      ?>
    </ul>
  </div> -->
<?php } ?>

<!-- <button type="button" onclick="refreshWorkshopFiles(<?php echo $workshop['id'];?>)">Refresh</button> -->

<?php if(!is_null($workshop['fmedia'])){?>
</div>
</div>
<?php } ?>

</div>

<?php } ?>

<?php

if($_SESSION['level']=="volunteer"){
  ?>
  <?php if($n_workshops_managing>0){ ?>
    <h3 style="color: #eee">All workshops</h3>
  <?php } ?>

  <?php

  $sql="SELECT * FROM `workshops` WHERE (workshops.id NOT IN (SELECT workshop_managers.workshop_id FROM `workshop_managers` WHERE workshop_managers.user_id=" . $_SESSION['id'] . ")) ORDER BY `workshops`.`start` DESC";

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
              <?php echo str_replace("resources","../resources",$workshop['fmedia']); ?>
            </div>

          </div>
          <div class="col-sm-9">
          <?php } ?>

          <!-- <span><?php echo $workshop['id']; ?></span> -->

          <a href="./view/workshop.php?workshop=<?php echo $workshop['id']; ?>"><h3 class="workshop-name"><?php echo $workshop['name']; ?></h3></a>
          <!-- Created on <?php echo date('m/d/Y h:i:s a', strtotime($workshop['created_on'])); ?> by <?php echo $creator['fname'] . " " . $creator['lname'] . " (" . $creator['username'] . ")"; ?><br /> -->

          <span class="glyphicon glyphicon-calendar"></span>
          <?php
          // echo date( 'd F Y (l) g:i:s A', strtotime( $workshop['start'] ));
          echo date( 'd F Y (l)', strtotime( $workshop['start'] ));
          ?>

          to
          <?php
          // echo date( 'd F Y (l) g:i:s A', strtotime( $workshop['end'] ));
          echo date( 'd F Y (l)', strtotime( $workshop['end'] ));
          ?><br />

          <span class="glyphicon glyphicon-map-marker"></span> <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($workshop['location']); ?>" target="_blank"><?php echo $workshop['location']; ?></a><br />
          <span class="glyphicon glyphicon-usd"></span><?php echo $workshop['registration_fee']; ?> (non refundable)<br />

          <?php
          $sql2="select * from workshop_registrants WHERE workshop_id='" . $workshop['id'] . "' and email='" . $_SESSION['email'] . "'";
          $request2=mysqli_query($conn, $sql2);
          if(mysqli_num_rows($request2)>0){
            //user has registered for this workshop
            ?>
            Registered<br />
            <?php
          }
          ?>

          <?php if($workshop['accepting']){
            ?>
            <button onclick="window.location='./actions/workshops/registration/register.php?workshop=<?php echo $workshop['id']; ?>'">Register</button>
            <?php
          }
          ?>

          <?php
          if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
            ?>
            <h4>Public Visibility</h4>
            <?php
            if($workshop['public']) {
              echo "Visible";
            }else{
              echo "Not visible";
            }
            ?>

            <h4>Accepting Registrations</h4>
            <?php
            if($workshop['accepting']) {
              echo "Yes";
            }else{
              echo "No";
            }
            ?>

            <?php
          }
          ?>

          <?php if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){ ?>
            <h4>Managing Team</h4>
            <ul class="workshop-members" id="workshop-<?php echo $workshop['id'];?>-members">
              <?php
              $sql2 = "select fname, lname, users.id, username from workshop_managers, users where workshop_managers.user_id = users.id and workshop_managers.workshop_id = '" . $workshop['id'] . "'";
              $query2 = mysqli_query($conn, $sql2);
              while($manager = mysqli_fetch_array($query2)){?>

                <li class="workshop-manager"><?php echo $manager["fname"] . " " . $manager["lname"]; ?> (<?php echo $manager["username"];?>)</li>

              <?php } ?>
            </ul>
          <?php } ?>

          <!-- <h4>Tags</h4>
          <ul class="workshop-tags" id="workshop-<?php echo $workshop['id'];?>-tags">
          <?php
          // $sql2 = "select tags from workshops where id = " . $workshop['id'];
          // $query2 = mysqli_query($conn, $sql2);
          // $result2 = mysqli_fetch_array($query2)["tags"];
          //
          // $tags = array_filter(explode(",",$result2));
          //
          // foreach ($tags as $tag){?>
          //
          //   <li class="workshop-tag"><?php echo $tag; ?></li>

          <?php //} ?>
        </ul> -->

        <!-- <div class="workshop-about">
        <?php echo $workshop['about']; ?>
      </div> -->

      <h4>Summary</h4>
      <?php echo $workshop['summary']; ?>

      <?php if(0){ ?>
        <!-- <div class="workshop-files-<?php echo $workshop['id']; ?>">
        <h4>Workshop Files</h4>
        <ul id="workshop-files-list-<?php echo $workshop['id'];?>">

        <?php
        $dir = "../resources/files/workshops/" . $workshop["id"] . "/";
        $filesList = scandir($dir);
        if (($key = array_search('.', $filesList)) !== false) {
        unset($filesList[$key]);
      }
      if (($key = array_search('..', $filesList)) !== false) {
      unset($filesList[$key]);
    }
    if (($key = array_search('.DS_Store', $filesList)) !== false) {
    unset($filesList[$key]);
  }
  foreach($filesList as $file){
  ?>
  <li><a href="<?php echo $dir.$file; ?>" /><span class="file-name"><?php echo $file; ?></span></a></li>
  <?php
}
?>
</ul>
</div> -->
<?php } ?>

<!-- <button type="button" onclick="refreshWorkshopFiles(<?php echo $workshop['id'];?>)">Refresh</button> -->

<?php if(!is_null($workshop['fmedia'])){?>
</div>
</div>
<?php } ?>

</div>

<?php }
}
?>

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
