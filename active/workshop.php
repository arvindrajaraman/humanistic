<?php
// This is the (private) page to edit a workshop

session_start();
if($_SESSION['login'] != "success"){
  header('Location: ./login.php');
}
if(!isset($_GET['id'])){
  header('Location: ./workshops.php');
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


<title>Humanistic Core - Edit Workshop</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../resources/css/common.css">
<link rel="stylesheet" type="text/css" href="../resources/css/workshops.css">

<script src="../resources/js/workshops.js"></script>

<!-- <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script> -->

<!-- include libraries(jQuery, bootstrap) -->
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>

<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>

<link rel="stylesheet" href="../resources/css/anytime.5.2.0.min.css" />
<script src="../resources/js/anytime.5.2.0.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<body>

  <!-- <div id="loading-div">
  <div id="loading-div-content">
  <img src="../resources/files/loading.svg" />
</div>
</div> -->

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

  <button class="back-light" onclick="location.href='./workshops.php'">View all workshops</button><br /><br />
  <!-- <button onclick="TogetherJS(this); return false;">Start Collaboration</button> -->

  <!-- <button id="start-togetherjs" type="button"
  onclick="TogetherJS(this); return false"
  data-end-togetherjs-html="End TogetherJS">
  Start TogetherJS
</button> -->

<div id="workshops">
  <?php

  if($_SESSION['level']=="admin"){
    $sql = "SELECT * FROM `workshops` WHERE id='" . $_GET['id'] . "'  ORDER BY `workshops`.`start` ASC";
  }else if($_SESSION['level']=="volunteer"){
    //page not visible for non admins and non volunteers, nothing done for this though
    $sql="SELECT * FROM `workshops` WHERE ((workshops.id IN (SELECT workshop_managers.workshop_id FROM `workshop_managers` WHERE workshop_managers.user_id=" . $_SESSION['id'] . ")) or workshops.creator_id = " . $_SESSION['id'] . ") and workshops.id = '" . $_GET['id'] . "'";
  }

  $query = mysqli_query($conn, $sql);

  $workshop_present = mysqli_num_rows($query)>=1;

  if(!$workshop_present){
    header('Location: ./workshops.php');
  }

  $workshop = "";

  while($workshop = mysqli_fetch_array($query)){

    // $sql3 = "SELECT * from users where users.id = " . $workshop['creator_id'];
    // $query3 = mysqli_query($conn, $sql3);
    // $creator = mysqli_fetch_array($query3);

    ?>

    <div class="well workshop" data-workshop-name="<?php echo $workshop['name']; ?>">
      <form id="workshop-<?php echo $workshop['id']; ?>" onsubmit="validateSubmitWorkshop(<?php echo $workshop['id']; ?>); return false;" action="actions/workshop/modify.php" method="POST">

        <div class="row">
          <div class="col-sm-3">

            <!-- HIDDEN FORM DATA -->
            <input type="hidden" name="workshop-id" value="<?php echo $workshop['id']; ?>" />
            <!-- <input type="hidden" name="action" value="update" /> -->
            <!-- /HIDDEN FORM DATA -->

            <span class="input-label">From</span><input id="workshop-startDate" type="text" name="startDate"><br />
            <span class="input-label">To</span><input id="workshop-endDate" type="text" name="endDate"><br />
            <span class="input-label">At</span><textarea id="workshop-location" name="location" type="text"><?php echo $workshop['location'];?></textarea>

            <script>
            $(document).ready(function() {
              $( "#workshop-startDate" ).datepicker();
              $( "#workshop-endDate" ).datepicker();
              $( "#workshop-startDate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
              $( "#workshop-endDate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
              $( "#workshop-startDate" ).datepicker( "setDate", "<?php echo date( 'Y-m-d', strtotime( $workshop['start'] )); ?>" );
              $( "#workshop-endDate" ).datepicker( "setDate", "<?php echo date( 'Y-m-d', strtotime( $workshop['end'] )); ?>" );
            });
            </script>

            <!-- <input type="text" id="field1" size="50"
            value="Sunday, July 30th in the Year 1967 CE" /><br/><input type="text" id="field2" value="12:34" />

            <script>
            AnyTime.picker( "field1",
            { format: "%W, %M %D in the Year %z %E", firstDOW: 1 } );
            $("#field2").AnyTime_picker(
            { format: "%H:%i", labelTitle: "Hour",
            labelHour: "Hour", labelMinute: "Minutes" } );
          </script> -->

          <h4>Public Visibility</h4>
          <input type="radio" name="visibility" value="1" <?php if($workshop['public']) echo "checked ";?>> Visible<br>
          <input type="radio" name="visibility" value="0" <?php if(!$workshop['public']) echo "checked ";?>> Not visible<br>

          <?php
          $workshop_id=$_GET['id'];
          $sql3 = "SELECT * FROM `workshop_registrants` where `workshop_id`='" . $workshop_id . "'";
          $query3 = mysqli_query($conn, $sql3);
          $registrant_count = mysqli_num_rows($query3);

          $sql3 = "SELECT * FROM `workshop_registrants` where `workshop_id`='" . $workshop_id . "' and confirmed='1'";
          $query3 = mysqli_query($conn, $sql3);
          $accepted_count = mysqli_num_rows($query3);

          ?>

          <h4>Accept Registrations</h4> (<?php echo $registrant_count;?> registrations so far, <?php echo $accepted_count; ?> accepted)<br />
          <input type="radio" name="accepting" value="1" <?php if($workshop['accepting']) echo "checked ";?>> Yes<br>
          <input type="radio" name="accepting" value="0" <?php if(!$workshop['accepting']) echo "checked ";?>> No<br>

          <h4>Registration Fee</h4>
          <span class="input-label">(In US Dollar)</span><input type="number" name="registration_fee" min="0" step="any" value="<?php echo $workshop["registration_fee"] ?>" >

          <h4>Registration limit</h4>
          <span class="input-label">Number of people</span><input type="number" min="1" name="max-population" value="<?php echo $workshop['max_population'];?>" placeholder="infinitely" />

          <h4>Featured Media</h4>
          <select id="workshop-fmedia-options-<?php echo $workshop['id'];?>" name="fmedia" onchange="if(this.value=='external') {document.getElementById('fmedia-<?php echo $workshop["id"];?>-external').style.display='block';} else{document.getElementById('fmedia-<?php echo $workshop["id"];?>-external').style.display='none';}">
            <option value="unchanged">Keep unchanged</option>
            <option value="NULL">None</option>
            <option value="external">&lt;&lt;External&gt;&gt;</option>

            <?php
            $fmedia_options = preg_grep('~\.(jpeg|jpg|png|gif|bmp|mp4)$~', scandir("../resources/files/workshops/" . $workshop["id"]));

            if (($key = array_search('.', $fmedia_options)) !== false) {
              unset($fmedia_options[$key]);
            }
            if (($key = array_search('..', $fmedia_options)) !== false) {
              unset($fmedia_options[$key]);
            }
            if (($key = array_search('.DS_Store', $fmedia_options)) !== false) {
              unset($fmedia_options[$key]);
            }
            foreach($fmedia_options as $fmedia_option){
              ?>
              <option value="<?php echo $fmedia_option; ?>"><?php echo $fmedia_option; ?></option>
              <?php
            }
            ?>
          </select>
          <!-- <button type="button" onclick="refreshFeatMedia(<?php echo $workshop['id'];?>)">Refresh</button> -->

          <input style="display:none;" id='fmedia-<?php echo $workshop['id'];?>-external' type="text" name="fmedia-external" placeholder='<iframe>...</iframe>' />

          <h4>Managing Team</h4>
          <ul class="workshop-managers" id="workshop-<?php echo $workshop['id'];?>-managers">
            <?php
            $sql2 = "select fname, lname, users.id, username from workshop_managers, users where workshop_managers.user_id = users.id and workshop_managers.workshop_id = " . $workshop['id'];
            $query2 = mysqli_query($conn, $sql2);
            while($manager = mysqli_fetch_array($query2)){
              $idSafeUserName = preg_replace("/[^a-zA-Z0-9]/", "", $manager['username']);
              ?>

              <li id="manager-<?php echo $manager['username'];?>" class="workshop-manager"><input name="workshop-manager[]" type="hidden" data-name="<?php echo $manager["fname"] . " " . $manager["lname"]; ?>" value="<?php echo $manager['username']; ?>"><?php echo $manager["fname"] . " " . $manager["lname"]; ?> <button type="button" onclick="removeManagerInList('<?php echo $manager["username"]; ?>')">Remove</button></li>

            <?php } ?>
          </ul>
          <ul>
            <li class="workshop-manager">
              <!-- <input type="text" onkeyup="managerSuggest(<?php echo $workshop['id']; ?>,this.value)"/> -->
              <!-- <div id="managerSuggest-<?php echo $workshop['id']; ?>"></div> -->

              <div class="autocomplete" style="width:300px;">
                <input data-name="" id="new-managerSuggest-<?php echo $workshop['id']; ?>" type="text" onkeyup="managerSuggest(<?php echo $workshop['id']; ?>,this.value)" autocomplete="off">
              </div>
              <!-- <button type=button onclick="addManagerInList(<?php echo $workshop['id']; ?>)">Add</button> -->
            </li>
          </ul>

          <!-- <h4>Tags</h4>
          <ul class="workshop-tags" id="workshop-<?php echo $workshop['id'];?>-tags"> -->
          <?php
          // $sql2 = "select tags from workshops_collab where id = " . $workshop['id'];
          // $query2 = mysqli_query($conn, $sql2);
          // $result2 = mysqli_fetch_array($query2)["tags"];
          //
          // $tags = array_filter(explode(",",$result2));
          //
          //
          // foreach ($tags as $tag){
          //   $idSafeTag = preg_replace("/[^a-zA-Z0-9]/", "", $tag);
          ?>

          <!-- <li class="workshop-tag"><input class="workshop-tag-input" name="workshop-tag[]" type="hidden" data-tag="<?php echo $tag; ?>" value="<?php echo $tag ?>"><?php echo $tag; ?> <button id="<?php echo $idSafeTag; ?>" onclick="removeTagInList(this, '<?php echo $idSafeTag; ?>');" type="button">Remove</button></li> -->

          <?php
          // }
          ?>
          <!-- </ul> -->

          <!-- <ul>
          <li class="workshop-tag">
          <!- - <input type="text" onkeyup="managerSuggest(<?php echo $workshop['id']; ?>,this.value)"/> - ->
          <!- - <div id="managerSuggest-<?php echo $workshop['id']; ?>"></div> - ->

          <div class="autocomplete" style="width:300px;">
          <input data-name="" id="new-tagSuggest-<?php echo $workshop['id']; ?>" type="text" onkeyup="return; tagSuggest(<?php echo $workshop['id']; ?>,this.value);" autocomplete="off">
        </div>
        <button type=button onclick="addTagInList(<?php echo $workshop['id']; ?>)">Add</button>
      </li>
    </ul> -->

  </div>
  <div class="col-sm-9">

    <span class="textbox-label">Workshop Name</span><h3><input class="full-width" name="workshop-name" value="<?php echo $workshop['name']; ?>" /></h3>

    <h4>Summary</h4>
    <span class="textbox-label">Visible on all workshops page</span>
    <textarea name="summary" class="summernote workshop-summary" style="width:100%; height:100%;"><?php echo $workshop['summary']; ?></textarea>

    <h4>About</h4>
    <span class="textbox-label">Visible on this workshop's page</span>
    <textarea name="about" class="summernote workshop-about" style="width:100%; height:100%;"><?php echo $workshop['about']; ?></textarea>

    <h4>Post registration message</h4>
    <span class="textbox-label">Visible on this workshop's page and only to accepted participants</span>
    <textarea name="post-reg-message" placeholder="Your registration is successful, excited to see your creativity in the workshop. Travel safe!" class="summernote workshop-post-reg-message" style="width:100%; height:100%;"><?php echo $workshop['post_registration_message']; ?></textarea>

    <h4>In workshop message</h4>
    <span class="textbox-label">Visible on this workshop's page and only to participants marked as present</span>
    <textarea name="in-workshop-message" placeholder="Welcome, come to the projector room on entering the hall." class="summernote workshop-in-workshop-message" style="width:100%; height:100%;"><?php echo $workshop['in_workshop_message']; ?></textarea>

    <h4>Questions For Registration Form</h4>
    <span class="textbox-label">Add questions (in addition to contact details) in the registration form</span>
    <div class="questions-container">
      <button class="add-question">New Question</button><br /><br />
      <div>
        <?php
        $questions = json_decode($workshop['registration_questions']);
        foreach ($questions as $question){
          ?>
          <div><textarea class="full-width" type="text" name="registration-question[]"><?php echo htmlspecialchars($question); ?></textarea><button href="#" class="delete">Delete</button><br /><br /></div>
          <?php
        }
        ?>
      </div>
    </div>

    <br />

  </div></div>

  <input class="full-width" type="submit" value="Publish Changes">
</form>

</div>

<div class="files-tile">
  <div class="workshop-files-<?php echo $workshop['id']; ?>">
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
        <li><a href="<?php echo $dir.$file; ?>" /><span class="file-name"><?php echo $file; ?></span></a> <button type="button" onclick="removeFile('<?php echo $file; ?>', <?php echo $workshop['id']; ?>, this)">Remove</button></li>
        <?php
      }
      ?>
    </ul>
  </div>

  <span class="input-label">Upload a new file</span>

  <form id="workshop-upload-<?php echo $workshop['id']; ?>" onsubmit="uploadFile(<?php echo $workshop['id'];?>, this); return false;" action="./actions/workshops/files/ajaxupload.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="workshop-id" value="<?php echo $workshop['id']; ?>" />
    <input id="uploadImage-<?php echo $workshop['id'];?>" type="file" name="image" />
    <!-- <input id="uploadImage-<?php echo $workshop['id'];?>" type="file" accept="image/*" name="image" /> -->
    <input type="submit" value="Upload">
  </form>
</div>

<!-- <button type="button" onclick="refreshWorkshopFiles(<?php echo $workshop['id'];?>)">Refresh</button> -->



<?php } ?>

</div>
</div>


<footer>
</footer>


<script>
$(document).ready(function() {
  var max_fields = 100;
  var wrapper = $(".questions-container");
  var add_button = $(".add-question");

  var x = 1;
  $(add_button).click(function(e) {
    e.preventDefault();
    if (x < max_fields) {
      x++;
      $(wrapper).append('<div><textarea class="full-width" type="text" name="registration-question[]"></textarea><button href="#" class="delete">Delete</button><br /><br /></div>'); //add input box
    } else {
      alert('You Reached the limits')
    }
  });

  $(wrapper).on("click", ".delete", function(e) {
    e.preventDefault();
    $(this).parent('div').remove();
    x--;
  })
});
</script>

<script>

$(document).ready(function() {

});


//variables
var managerSuggestedName=""

// document.addEventListener('input', function (event) {
//   if (event.target.tagName.toLowerCase() !== 'textarea') return;
//   autoExpand(event.target);
// }, false);

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

var textAreas = document.getElementsByClassName('workshop-about');

// $(document).ready(function() {
//   $(".summernote").summernote();
// });


for(var i=0; i<textAreas.length; i++){
  autoExpand(textAreas[i]);
  textAreas[i].addEventListener("focusout", updateCollabDB);
  // textAreas[i].summernote();
}

function removeFile(file, id, buttonRef){
  var xhr = new XMLHttpRequest();
  xhr.open("POST", './actions/workshops/files/delete.php', true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function() {//Call a function when the state changes.
    if(this.readyState == XMLHttpRequest.DONE && this.status == 200) {

      if(xhr.responseText=="success"){
        buttonRef.parentNode.parentNode.removeChild(buttonRef.parentNode);
        refreshFeatMedia(id);

        if (filesChangedFromRemote) {
          return;
        }
        TogetherJS.send({type: "filesChanged"});

      }else{
        window.alert("File could not be deleted!");
        console.log(xhr.responseText);
      }
    }
  }

  xhr.send("workshop-id="+id+"&file="+file);
}

function refreshWorkshopFiles(id){
  var listElement = document.getElementById('workshop-files-list-'+id);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", './actions/workshops/files/list.php', true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function() {//Call a function when the state changes.
    if(this.readyState == XMLHttpRequest.DONE && this.status == 200) {
      var files = JSON.parse(xhr.responseText);
      console.log(xhr.responseText);

      listElement.innerHTML="";

      for (var key in files) {
        var file = files[key];

        listElement.innerHTML = listElement.innerHTML + ("<li><a href='../resources/files/workshops/" +id+"/"+file+"' /><span class='file-name'>"+file+"</span></a> <button class='btn btn-primary' type='button' onclick='removeFile(\""+file+"\", "+id+", this)'>Remove</button></li>");

      }

      // console.log(xhr.responseText);
    }
  }

  xhr.send("workshop-id="+id);

}

function refreshFeatMedia(id){

  var optionsList = document.getElementById("workshop-fmedia-options-"+id);
  var nOptions = optionsList.childNodes;

  // console.log(nOptions.length); //includes whitespaces

  while(nOptions.length!=0){
    //removing all children
    optionsList.removeChild(optionsList.childNodes[0]);
  }

  var newOptionLi = document.createElement("OPTION");
  newOptionLi.value = "unchanged";
  newOptionLi.text = "Keep unchanged";
  optionsList.appendChild(newOptionLi);

  var newOptionLi = document.createElement("OPTION");
  newOptionLi.value = "NULL";
  newOptionLi.text = "None";
  optionsList.appendChild(newOptionLi);

  var newOptionLi = document.createElement("OPTION");
  newOptionLi.value = "external";
  newOptionLi.text = "<<External>>";
  optionsList.appendChild(newOptionLi);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", './actions/workshops/files/list.php', true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function() {//Call a function when the state changes.
    if(this.readyState == XMLHttpRequest.DONE && this.status == 200) {
      var newOptions = JSON.parse(xhr.responseText);
      // console.log(newOptions);

      // $supported_images = Array("jpg","jpeg","png","gif","bmp");
      // $supported_videos = Array("mp4");

      for (var key in newOptions) {
        var newOption = newOptions[key];
        if(newOption.includes("jpg") || newOption.includes("jpeg") || newOption.includes("png") || newOption.includes("gif") || newOption.includes("bmp") || newOption.includes("mp4")){
          var newOptionLi = document.createElement("OPTION");
          newOptionLi.value = newOption;
          newOptionLi.text = newOption;
          optionsList.appendChild(newOptionLi);
        }else{
          continue;
        }
      }

      // console.log(xhr.responseText);
    }
  }

  xhr.send("workshop-id="+id);
}

function managerSuggest(id, string){

  // var resultBox = document.getElementById("managerSuggest-"+id);
  if(string=="" || string=="@") {
    // resultBox.innerHTML="";
    return;
  };
  var http = new XMLHttpRequest();
  var url = './aux/memberSuggest.php';
  var params = 'string='+string+'&level=volunteer';
  http.open('POST', url, true);

  //Send the proper header information along with the request
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
      // window.alert(http.responseText);

      console.log(http.responseText);
      var suggestions = JSON.parse(http.responseText);
      // resultBox.innerHTML="";
      var names = new Array();

      for (var i=0; i<suggestions.length; i++){
        // resultBox.innerHTML = resultBox.innerHTML + suggestions[i]["fname"];
        names.push(suggestions[i]["fname"]+" "+suggestions[i]["lname"] + " (@" + suggestions[i]["username"] + ")");
      }

      // window.alert(names);

      function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function(e) {
          var a, b, i, val = this.value;
          /*close any already open lists of autocompleted values*/
          closeAllLists();
          if (!val) { return false;}
          currentFocus = -1;
          /*create a DIV element that will contain the items (values):*/
          a = document.createElement("DIV");
          a.setAttribute("id", this.id + "autocomplete-list");
          a.setAttribute("class", "autocomplete-items");
          /*append the DIV element as a child of the autocomplete container:*/
          this.parentNode.appendChild(a);
          /*for each item in the array...*/
          for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (true) {
              /*create a DIV element for each matching element:*/
              b = document.createElement("DIV");
              /*make the matching letters bold:*/
              b.innerHTML = "" + arr[i].substr(0, val.length) + "";
              b.innerHTML += arr[i].substr(val.length);
              /*insert a input field that will hold the current array item's value:*/
              b.innerHTML += "<input type='hidden' value='" + suggestions[i]["username"] + "'>";
              b.innerHTML += "<input type='hidden' value='" + suggestions[i]["fname"] + "'>";
              b.innerHTML += "<input type='hidden' value='" + suggestions[i]["lname"] + "'>";
              /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value;
                managerSuggestedName = this.getElementsByTagName("input")[1].value + " " + this.getElementsByTagName("input")[2].value;
                inp.setAttribute("data-name", managerSuggestedName);
                inp.setAttribute("data-username", this.getElementsByTagName("input")[0].value);
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();

                managerClicked(this.getElementsByTagName("input")[0].value, managerSuggestedName);

              });
              a.appendChild(b);
            }
          }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
          var x = document.getElementById(this.id + "autocomplete-list");
          if (x) x = x.getElementsByTagName("div");
          if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
          } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
          } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
              /*and simulate a click on the "active" item:*/
              if (x) x[currentFocus].click();
            }
          }
        });
        function addActive(x) {
          /*a function to classify an item as "active":*/
          if (!x) return false;
          /*start by removing the "active" class on all items:*/
          removeActive(x);
          if (currentFocus >= x.length) currentFocus = 0;
          if (currentFocus < 0) currentFocus = (x.length - 1);
          /*add class "autocomplete-active":*/
          x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
          /*a function to remove the "active" class from all autocomplete items:*/
          for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
          }
        }
        function closeAllLists(elmnt) {
          /*close all autocomplete lists in the document,
          except the one passed as an argument:*/
          var x = document.getElementsByClassName("autocomplete-items");
          for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
              x[i].parentNode.removeChild(x[i]);
            }
          }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
          closeAllLists(e.target);
        });
      }

      autocomplete(document.getElementById("new-managerSuggest-"+id), names);
    }
  }
  http.send(params);
}

function validateSubmitWorkshop(id){

  //checking number of managers
  var managersUL = document.getElementById('workshop-'+id+'-managers');
  // if((managersUL.getElementsByTagName('li')).length<1){
  //   window.alert("Please add a manager!");
  //   return false;
  // }


  //everything (in the form) seems okay
  var data2send = JSON.stringify($("#workshop-"+id).serialize());

  data2send=data2send.substring(1, data2send.length-1);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", './actions/workshops/modify.php', true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function() {//Call a function when the state changes.
    if(this.readyState == XMLHttpRequest.DONE && this.status == 200) {
      if(xhr.responseText == "success"){
        window.alert("Successfully published all changes!");
      }else{
        window.alert(xhr.responseText);
      }
    }
  }

  xhr.send(data2send);

  return false;
}

function managerClicked(username, name){

  addManagerInList(<?php echo $_GET['id']; ?>, username, name);

  if (managerClickedFromRemote) {
    return;
  }
  TogetherJS.send({type: "managerClicked", uname: username, fullname: name});

}

function removeManagerInList(manager_username){

  // window.alert("removeManagerInList called");
  // window.alert("removeManagerFromList called to remove: "+manager_username);

  listElement = document.getElementById("manager-"+manager_username);
  listElement.remove();

  updateCollabDB();

  if (managerRemovedFromRemote) {
    return;
  }
  TogetherJS.send({type: "managerRemoved", uname: manager_username});

}

function addManagerInList(workshop_id, username="", name=""){

  var finalUsername, finalName;
  var field = document.getElementById('new-managerSuggest-'+workshop_id);

  if(username===""){
    finalUsername=field.getAttribute("data-username");
  }else{
    finalUsername=username;
  }

  if(name===""){
    finalName=field.getAttribute("data-name");
  }else{
    finalName=name;
  }

  var managersList = document.getElementById('workshop-'+workshop_id+'-managers');
  var newListElement = "<li class=\"workshop-manager\" id=\"manager-"+finalUsername+"\"><input name=\"workshop-manager[]\" type=\"hidden\" value=\"" +
  finalUsername +
  "\">" +
  finalName +
  " <button class=\"btn btn-primary\" type=\"button\" onclick=\"removeManagerInList('" + finalUsername + "')\">Remove</button></li>";


  managersList.innerHTML = managersList.innerHTML + newListElement;
  field.value="";

  updateCollabDB();

  // if (managerAddedFromRemote) {
  //   return;
  // }
  // TogetherJS.send({type: "managerAdded", uname: finalUsername, name: finalName});

  //<li class="workshop-manager"><input name="workshop-manager[]" type="hidden" value="<?php echo $manager['username']; ?>"><?php echo $manager["fname"] . " " . $manager["lname"]; ?> <button onclick="this.parentNode.parentNode.removeChild(this.parentNode)">Remove</button></li>

}

function removeTagInList(element, tagName){

  element.parentNode.parentNode.removeChild(element.parentNode);
  updateCollabDB();

  if (removeTagFromRemote) {
    return;
  }
  // var elementFinder = TogetherJS.require("elementFinder");
  // var location = elementFinder.elementLocation(element);
  TogetherJS.send({type: "tagRemoved", tag: tagName});
}

function addTagInList(workshop_id, content=""){

  var tagName;

  if(content==""){
    tagName = document.getElementById('new-tagSuggest-'+workshop_id).value;
  }else{
    tagName = content;
  }

  if(tagName===""){
    return;
  }

  var field = document.getElementById('new-tagSuggest-'+workshop_id);
  var tagsList = document.getElementById('workshop-'+workshop_id+'-tags');
  var idSafeTag = tagName.replace(/[^a-zA-Z]/g, '');
  var newListElement = "<li class=\"workshop-tag\"><input name=\"workshop-tag[]\" type=\"hidden\" value=\"" +
  tagName +
  "\"> " +
  tagName +
  " <button class=\"btn btn-primary\" id=\"" + idSafeTag + "\" onclick=\"removeTagInList(this, '" + idSafeTag + "');\" type=\"button\">Remove</button></li>";

  tagsList.innerHTML = tagsList.innerHTML + newListElement;
  field.value="";

  updateCollabDB();

  if (tagAddedFromRemote) {
    return;
  }
  TogetherJS.send({type: "tagAdded", tag: tagName});

  //<li class="workshop-manager"><input name="workshop-manager[]" type="hidden" value="<?php echo $manager['username']; ?>"><?php echo $manager["fname"] . " " . $manager["lname"]; ?> <button onclick="this.parentNode.parentNode.removeChild(this.parentNode)">Remove</button></li>
}

function updateCollabDB(){

  return;

  // var inputs=document.getElementsByClassName('workshop-tag-input');
  // var tags = [];
  //
  // for(i=0; i<inputs.length;i++){
  //   tags.push(inputs[i].value);
  // }
  //
  // tags=JSON.stringify(tags);
  // tags=tags.substring(1, tags.length-1)

  var data2send = JSON.stringify($("#workshop-"+<?php echo $_SESSION['id']; ?>).serialize());

  data2send=data2send.substring(1, data2send.length-1);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", './actions/workshops/aux/update_collab_base.php', true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function() {//Call a function when the state changes.
    if(this.readyState == XMLHttpRequest.DONE && this.status == 200) {
      if(xhr.responseText == "success"){
        console.log("Collab LIVE Updated");
      }else{
        window.alert(xhr.responseText);
      }
    }
  }

  xhr.send(data2send);


}

</script>

<script>

function uploadFile(id, form) {
  // alert("file form submitted");
  $.ajax({
    url: "./actions/workshops/files/ajaxupload.php",
    type: "POST",
    data:  new FormData(form),
    contentType: false,
    cache: false,
    processData:false,
    beforeSend : function()
    {
      //window.alert('uploading');
    },
    success: function(data)
    {
      if(data=='invalid')
      {
        window.alert("Invalid file");
      }
      else
      {
        window.alert("Uploaded!");
        form.reset();
        refreshWorkshopFiles(id);
        refreshFeatMedia(id);

        if (filesChangedFromRemote) {
          return;
        }
        TogetherJS.send({type: "filesChanged"});

      }
    },
    error: function(e)
    {
      window.alert("Upload failed");
      console.log(e);
    }
  });
}
</script>

<!-- <button id="mybutton" onclick="myPut()">click</button> -->

<script>

var TogetherJSConfig_on = {
  ready: function () {
    // window.alert('ready!');

    document.getElementById('loading-div').style.display="None";

    TogetherJS.hub.on("visibilityChange", function (msg) {
      if (! msg.sameUrl) {
        return;
      }
      var elementFinder = TogetherJS.require("elementFinder");
      // If the element can't be found this will throw an exception:
      var element = elementFinder.findElement(msg.element);
      visibilityChangeFromRemote = true;
      try {
        // MyApp.changeVisibility(element, msg.isVisible);
        console.log(element);
        console.log(msg.isVisible);
        window.alert("clicked");
      } finally {
        visibilityChangeFromRemote = false;
      }
    });

    TogetherJS.hub.on("tagAdded", function (msg) {
      if (! msg.sameUrl) {
        return;
      }
      // var elementFinder = TogetherJS.require("elementFinder");
      // // If the element can't be found this will throw an exception:
      // var element = elementFinder.findElement(msg.element);
      tagAddedFromRemote = true;
      try {
        // MyApp.changeVisibility(element, msg.isVisible);
        // console.log(element);
        // console.log(msg.isVisible);
        // window.alert("clicked");
        // window.alert('tag added');
        addTagInList(<?php echo $_GET['id'];?>, msg.tag);
      } finally {
        tagAddedFromRemote = false;
      }
    });

    TogetherJS.hub.on("tagRemoved", function (msg) {
      if (! msg.sameUrl) {
        return;
      }
      // var elementFinder = TogetherJS.require("elementFinder");
      // If the element can't be found this will throw an exception:
      // var element = elementFinder.findElement(msg.element);
      removeTagFromRemote = true;
      try {
        // MyApp.changeVisibility(element, msg.isVisible);
        // console.log(element);
        // console.log(msg.tag);
        // window.alert(msg.tag);

        var element = document.getElementById(msg.tag);
        element.parentNode.parentNode.removeChild(element.parentNode);

      } finally {
        removeTagFromRemote = false;
      }
    });

    TogetherJS.hub.on("filesChanged", function (msg) {
      if (! msg.sameUrl) {
        return;
      }
      // var elementFinder = TogetherJS.require("elementFinder");
      // If the element can't be found this will throw an exception:
      // var element = elementFinder.findElement(msg.element);
      filesChangedFromRemote = true;
      try {
        // MyApp.changeVisibility(element, msg.isVisible);

        refreshWorkshopFiles(<?php echo $_GET['id']; ?>);
        refreshFeatMedia(<?php echo $_GET['id']; ?>);

      } finally {
        filesChangedFromRemote = false;
      }
    });

    TogetherJS.hub.on("managerClicked", function (msg) {
      if (! msg.sameUrl) {
        return;
      }
      // var elementFinder = TogetherJS.require("elementFinder");
      // If the element can't be found this will throw an exception:
      // var element = elementFinder.findElement(msg.element);
      managerClickedFromRemote = true;
      try {
        // MyApp.changeVisibility(element, msg.isVisible);
        // console.log(element);
        // console.log(msg.isVisible);
        // window.alert("clicked");

        addManagerInList(<?php echo $_GET['id']; ?>, msg.uname, msg.fullname);

      } finally {
        managerClickedFromRemote = false;
      }
    });

    TogetherJS.hub.on("managerRemoved", function (msg) {
      if (! msg.sameUrl) {
        return;
      }
      // var elementFinder = TogetherJS.require("elementFinder");
      // If the element can't be found this will throw an exception:
      // var element = elementFinder.findElement(msg.element);
      managerRemovedFromRemote = true;
      try {
        // MyApp.changeVisibility(element, msg.isVisible);
        // console.log(element);
        // console.log(msg.tag);
        // window.alert(msg.uname);

        removeManagerInList(msg.uname);

      } finally {
        managerRemovedFromRemote = false;
      }
    });

  }
};

// TogetherJS_hub_on = {
//   "my-event": function (msg) {
//   }
// };

TogetherJSConfig_getUserName = function () {return '<?php echo $_SESSION['fname'] . " " . $_SESSION['lname'] ;?>';};
TogetherJSConfig_findRoom = {prefix: "togetherjsmadlibs<?php echo $_GET['id'];?>", max: 100};
TogetherJSConfig_autoStart = false;
TogetherJSConfig_suppressJoinConfirmation = true;
TogetherJSConfig_storagePrefix = "tjs_madlibs<?php echo $_GET['id'];?>";

var visibilityChangeFromRemote = false;
var tagAddedFromRemote = false;
var removeTagFromRemote = false;
var filesChangedFromRemote = false;
var managerClickedFromRemote = false;
var managerRemovedFromRemote = false;


function myPut(){
  if (visibilityChangeFromRemote) {
    return;
  }
  var elementFinder = TogetherJS.require("elementFinder");
  var location = elementFinder.elementLocation(document.getElementById('mybutton'));
  TogetherJS.send({type: "visibilityChange", isVisible: true, element: location});
}

</script>
<script src="https://togetherjs.com/togetherjs-min.js"></script>
</body>
</html>
