<?php
// This page is the (public) projects page

session_start();
//connecting to the database for fetching projects
require_once("../../../../connections/db_connect.php");

if(!isset($_GET['workshop']))
header('Location:../../../workshops.php');

$sql = "select * from workshop_managers where workshop_id = '" . $_GET['workshop'] . "' and user_id = '" . $_SESSION['id'] . "'";
$workshop_manager = mysqli_num_rows(mysqli_query($conn, $sql))>0;

if(!(isset($_SESSION['login']) && $_SESSION['login'] == "success" && ($_SESSION['level']=="admin" || $workshop_manager))){
  header('Location: ../../../view/workshop.php?workshop=' . $_GET['workshop']);
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


<title>Humanistic Core - Workshop Registrants</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../../../../resources/css/common.css">
<link rel="stylesheet" type="text/css" href="../../../../resources/css/register.css">
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
            <li><a href="../../../overview.php">Overview</a></li>
            <li><a href="../../../projects.php">Projects</a></li>
            <li><a href="../../../community.php">Community</a></li>
            <li class="active"><a href="../../../workshops.php">Workshops</a></li>
            <!-- <li><a href="../../../contact.php">Contact</a></li> -->
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li><a href="../../../profile.php"><span class="glyphicon glyphicon glyphicon-user"></span> <?php echo $_SESSION['fname']; ?></a></li>
            <li><a href="../../../logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
          </ul>

        </div>
      </div>
    </nav>
  </header>

  <div class="container">

    <a href="../../../workshops.php">Back to all workshops</a>

    <?php
    // $sql = "select * from workshops where id='" . $_GET['workshop'] . "'";

    // if($_SESSION['level']=="admin"){
    if(1){
      $sql = "SELECT * FROM `workshops` WHERE id='" . $_GET['workshop'] . "'  ORDER BY `workshops`.`start` ASC";
    }else if($_SESSION['level']=="volunteer"){
      //page not visible for non admins and non volunteers, nothing done for this though
      $sql="SELECT * FROM `workshops` WHERE ((workshops.id IN (SELECT workshop_managers.workshop_id FROM `workshop_managers` WHERE workshop_managers.user_id=" . $_SESSION['id'] . ")) or workshops.creator_id = " . $_SESSION['id'] . ") and workshops.id = '" . $_GET['workshop'] . "'";
    }

    $query = mysqli_query($conn, $sql);

    $workshop_present = mysqli_num_rows($query)>=1;

    if(!$workshop_present){
      header('Location: ../../../workshops.php');
    }

    $workshop = mysqli_fetch_array($query);

    ?>

    <div class="workshop" data-workshop-name="<?php echo $workshop['name']; ?>">

      <h3 class="workshop-name"><?php echo $workshop['name']; ?></h3>

      <?php
      // echo date( 'd F Y (l) g:i:s A', strtotime( $workshop['start'] ));
      echo date( 'd F Y (l)', strtotime( $workshop['start'] ));
      ?>

      to
      <?php
      // echo date( 'd F Y (l) g:i:s A', strtotime( $workshop['end'] ));
      echo date( 'd F Y (l)', strtotime( $workshop['end'] ));
      ?><br />

      <?php echo $workshop['location']; ?><br /><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($workshop['location']); ?>" target="_blank">View on Google Maps</a><br />

      <!-- <h4>Managing Team</h4>
      <ul class="workshop-members" id="workshop-<?php echo $workshop['id'];?>-members">
      <?php
      $sql2 = "select fname, lname, users.id, username from workshop_managers, users where workshop_managers.user_id = users.id and workshop_managers.workshop_id = '" . $workshop['id'] . "'";
      $query2 = mysqli_query($conn, $sql2);
      while($manager = mysqli_fetch_array($query2)){?>

      <li class="workshop-manager"><?php echo $manager["fname"] . " " . $manager["lname"]; ?> (<?php echo $manager["username"];?>)</li>

    <?php } ?>
  </ul> -->
  <br />

  <div class="registant-notify well">
    <h4>Notify</h4>
    Send an email to participants from no-reply@humanistic.app<br /><br />
    <form method="POST" action="./aux/email_notify.php" onsubmit="return confirm('Are you sure? This is irreversible.')">

      <span class="input-label">Registration Status</span>
      <input type="radio" name="registration_status" value="accepted"> Accepted
      <input type="radio" name="registration_status" value="rejected"> Rejected
      <input type="radio" name="registration_status" value="undecided" checked> Undecided
      <input type="radio" name="registration_status" value="all" checked> All

      <br />

      <span class="input-label">Payment Status</span>
      <input type="radio" name="payment_status" value="paid"> Paid
      <input type="radio" name="payment_status" value="unpaid"> Unpaid
      <input type="radio" name="payment_status" value="both" checked> Both

      <br />

      <span class="input-label">Workshop Presence</span>
      <input type="radio" name="presence_status" value="present"> Present
      <input type="radio" name="presence_status" value="not_present"> Not Present
      <input type="radio" name="presence_status" value="both" checked> Both

      <br />

      <input type="hidden" name="workshop_id" value="<?php echo $workshop['id']; ?>" />

      <span class="input-label">Subject</span>
      <input required type="text" name="subject" /><br />

      <span class="input-label">Content</span>
      <textarea required name="content"></textarea><br />

      <input type="submit" value="Send" />

      <?php if(isset($_SESSION['messages']['email-notification'])){ ?><span class="form-notification"><?php echo $_SESSION['messages']['email-notification']; ?></span> <?php unset($_SESSION['messages']['email-notification']); } ?>
    </form>
  </div>

  <h4>Registrations</h4>

  <?php
  $workshop_id=$_GET['workshop'];
  $sql = "SELECT * FROM `workshop_registrants` where `workshop_id`='" . $workshop_id . "'";
  $query = mysqli_query($conn, $sql);
  $count = mysqli_num_rows($query);

  $sql2 = "SELECT * FROM `workshop_registrants` where `workshop_id`='" . $workshop_id . "' and confirmed='1'";
  $query2 = mysqli_query($conn, $sql2);
  $countConfirmed = mysqli_num_rows($query2);

  $sql2 = "SELECT * FROM `workshop_registrants` where `workshop_id`='" . $workshop_id . "' and present='1'";
  $query2 = mysqli_query($conn, $sql2);
  $countPresent = mysqli_num_rows($query2);

  $sql2 = "SELECT * FROM `workshop_registrants` where `order_id` LIKE 'CNTRCASH%'";
  $query2 = mysqli_query($conn, $sql2);
  $countCash = mysqli_num_rows($query2);

  ?>

  <?php
  if($count!=1) echo "$count people have registered for this workshop";
  else echo "$count person has registered for this workshop";
  ?>
  <br />

  (<span id='confirmed-count'><?php echo $countConfirmed; ?></span> accepted, <span id='present-count'><?php echo $countPresent; ?></span> present, <span id="cash-count"><?php echo $countCash; ?></span> cash giver(s))

  <?
  $nPeople = 0;
  $waitList = 0;

  while($registrant = mysqli_fetch_array($query)){
    $nPeople++;
    $waiting = $nPeople>$workshop['max_population'];
    ?>

    <?php if($waiting && $waitList==0){
      $waitList=1;?>
      <h4>Waitlist</h4>
      <?php
    }
    ?>

    <div class="registrant well">
      <h5 class="registrant-name"><?php echo $registrant['fname'] . " " . $registrant['lname'];?></h5>
      <ul>
        <li>Email: <a href="mailto:<?php echo $registrant['email'];?>"><?php echo $registrant['email'];?></a></li>
        <li>Phone: <a href="tel:<?php echo $registrant['phone'];?>"><?php echo $registrant['phone'];?></a></li>
        <li>Address: <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($registrant['address']); ?>" target="_blank"><?php echo $registrant['address'];?></a></li>
        <li>
          <button onclick="document.getElementById('responses-<?php echo $registrant["id"];?>').style.display='Block'; this.disabled=true;">View responses</button>
          <ul id="responses-<?php echo $registrant['id'];?>" style="display:none;">
            <?php
            $responses = json_decode($registrant['responses']);
            foreach($responses as $question=>$answer){
              ?>
              <li><?php echo $question;?><br />
                <?php echo $answer;?></li>
                <?php
              }
              ?>
            </ul>

          </li>
        </ul>

        <div id="registrant-invite-box-<?php echo $registrant['id']; ?>" class='team_member'>
          <?php
          $sql = "SELECT * from users where email='" . $registrant['email'] . "'";
          $result = mysqli_query($conn, $sql);
          $registrant_info_from_DB = mysqli_fetch_array($result);
          if(mysqli_num_rows($result)==0){
            ?>
            <form id="invite-registrant-<?php echo $registrant['id']; ?>" action='../../members/invite.php' onsubmit="inviteRegistrant(<?php echo $registrant['id']; ?>); return false;" method='post'>
              <input name='category' type='hidden' value='member' />
              <input name='fname' type='hidden' value='<?php echo $registrant['fname'];?>' />
              <input name='lname' type='hidden' value='<?php echo $registrant['lname'];?>' />
              <input name='email' type='hidden' value='<?php echo $registrant['email'];?>' />
              <input name='invite' type='hidden' value='ajax' />
              <input type='submit' name='invite-registrant' value='Invite to community'/>
            </form>

            <?php
          }else if($registrant_info_from_DB['verified']=='0'){
            ?>
            Community invite sent
            <?php
          }else if($registrant_info_from_DB['verified']=='1'){
            $linkdata = array(
              'username' => $manager['username']
            );
            ?>
            Community member (<a target="_blank" href="../../../profile.php?<?php echo http_build_query($linkdata);?>">@<?php echo $registrant_info_from_DB['username']; ?></a>)
            <?php
          }
          ?>
        </div><br />


        <div id="registrant-update-box-<?php echo $registrant['id']; ?>" class='team_member'>

          <?php if(!$registrant['present']){ ?>

            <form id="registrant-<?php echo $registrant['id']; ?>" action='./aux/update_registration.php' onsubmit="return false;" method='post'>
              <input name='workshop-id' type='hidden' value='<?php echo $workshop_id;?>' />
              <input name='fname' type='hidden' value='<?php echo $registrant['fname'];?>' />
              <input name='lname' type='hidden' value='<?php echo $registrant['lname'];?>' />
              <input name='registrant-email' type='hidden' value='<?php echo $registrant['email'];?>' />
              Registration:
              <?php
              if(!$registrant['confirmed']){
                ?>
                <input type='submit' onclick="updateRegistrant(<?php echo $registrant['id']; ?>, 'accept')" name='accept' value='Accept'/>
                <?php if($registrant['cancelled']==1){ ?><br />(Cancellation already intimated)<?php } ?>
                <?php
              }else if($registrant['confirmed']){ ?>
                <input type='submit' onclick="updateRegistrant(<?php echo $registrant['id']; ?>, 'reject')" name='reject' value='Cancel'/>
                <input type='submit' onclick="updateRegistrant(<?php echo $registrant['id']; ?>, 'present')" name='present' value='Confirm Presence'/>
                <?php
              }
              ?>

            </form>

          <?php } else{ ?>
            Present
          <?php } ?>

        </div>

        <br />


        <div id="registrant-payment-info-box-<?php echo $registrant['id']; ?>">

          <?php if(!$registrant['paid']){ ?>

            <form id="registrant-<?php echo $registrant['id']; ?>" action='./aux/update_registration.php' onsubmit="return false;" method='post'>
              <input name='workshop-id' type='hidden' value='<?php echo $workshop_id;?>' />
              <input name='fname' type='hidden' value='<?php echo $registrant['fname'];?>' />
              <input name='lname' type='hidden' value='<?php echo $registrant['lname'];?>' />
              <input name='registrant-email' type='hidden' value='<?php echo $registrant['email'];?>' />
              Payment:
              <input type='submit' onclick="updateRegistrant(<?php echo $registrant['id']; ?>, 'payment')" name='accept' value='Accept Cash'/>
            </form>


          <?php } else{ ?>
            Paid (Order ID: <?php echo $registrant["order_id"] ; ?>)
          <?php } ?>

        </div>



      </div>

      <?php
    } ?>

  </div>

  <footer>&nbsp;
  </footer>

  <script>
  function updateRegistrant(id, action){

    if(action.localeCompare("payment")==0){
      r = confirm("Are you sure? This can not be undone.");
      if(r==false)
      return false;
    }

    var form = $("#registrant-"+id);
    var url = form.attr('action');
    var data2send = form.serialize() + '&'+ action + '=true';

    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {//Call a function when the state changes.
      if(this.readyState == XMLHttpRequest.DONE && this.status == 200) {
        if(xhr.responseText == "success"){
          if(action.localeCompare("accept")==0){
            document.getElementById('registrant-update-box-'+id).innerHTML="Registration accepted";
            document.getElementById('confirmed-count').innerHTML = parseInt(document.getElementById('confirmed-count').innerHTML)+1;
          }else if(action.localeCompare("reject")==0){
            document.getElementById('registrant-update-box-'+id).innerHTML="Registration rejected";
            document.getElementById('confirmed-count').innerHTML = parseInt(document.getElementById('confirmed-count').innerHTML)-1;
          }else if(action.localeCompare("present")==0){
            document.getElementById('registrant-update-box-'+id).innerHTML="Marked present";
            document.getElementById('present-count').innerHTML = parseInt(document.getElementById('present-count').innerHTML)+1;
          }else if(action.localeCompare("payment")==0){
            document.getElementById('registrant-payment-info-box-'+id).innerHTML = "Marked paid";
            document.getElementById('cash-count').innerHTML = parseInt(document.getElementById('cash-count').innerHTML)+1;
          }
        }else{
          window.alert(xhr.responseText);
        }
      }
    }

    xhr.send(data2send);

    return false;
  }

  function inviteRegistrant(id){

    var form = $("#invite-registrant-"+id);
    var url = form.attr('action');
    var data2send = form.serialize();

    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {//Call a function when the state changes.
      if(this.readyState == XMLHttpRequest.DONE && this.status == 200) {
        if(xhr.responseText == "success"){
          document.getElementById('registrant-invite-box-'+id).innerHTML="Community invite sent";
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
