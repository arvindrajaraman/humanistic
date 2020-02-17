<?php
// This is the (private) page to edit a project

session_start();
if($_SESSION['login'] != "success"){
  header('Location: ./login.php');
}
if(!isset($_GET['id'])){
  header('Location: ./projects.php');
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


<title>Humanistic Core - Edit Project</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.tiny.cloud/1/t84em2r3wihidm2o5l9x94acedy68e026hovky70wthqxdqh/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../resources/css/common.css">
<link rel="stylesheet" type="text/css" href="../resources/css/projects.css">

<!-- <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script> -->

<!-- include libraries(jQuery, bootstrap) -->

<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>

<!-- <script>tinymce.init({selector:'textarea'});</script> -->

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
            <li class="active"><a href="#">Projects</a></li>
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
            <li><a href="console.php"><span class="glyphicon glyphicon glyphicon-cog"></span> Console</a></li>
          <?php }?>
            <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>Sign Up</a></li> -->
            <li><a href="profile.php"><span class="glyphicon glyphicon glyphicon-user"></span> <?php echo $_SESSION['fname']; ?></a></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
          </ul>

        </div>
      </div>
    </nav>
  </header>


  <div id="loading-div">
    <div id="loading-div-content">
      <img src="../resources/files/loading.svg" />
    </div>
  </div>

  <div class="container">

    <button class="back-light" onclick="location.href='./projects.php'">View all projects</button><br /><br />
    <!-- <button onclick="TogetherJS(this); return false;">Start Collaboration</button> -->

    <!-- <button id="start-togetherjs" type="button"
    onclick="TogetherJS(this); return false"
    data-end-togetherjs-html="End TogetherJS">
    Start TogetherJS
  </button> -->

  <div id="projects">
    <?php

    $sql = "SELECT * from project_stages order by sequence_number ASC";
    $query = mysqli_query($conn, $sql);
    $stages = array();
    while($stage = mysqli_fetch_array($query)) array_push($stages,$stage['name']);

    if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
      $sql = "SELECT * FROM `projects_collab` WHERE id='" . $_GET['id'] . "' ORDER BY `projects_collab`.`created_on` ASC";
    }else{
      $sql="SELECT * FROM `projects_collab` WHERE ((projects_collab.id IN (SELECT project_members_collab.project_id FROM `project_members_collab` WHERE project_members_collab.user_id=" . $_SESSION['id'] . ")) or projects_collab.creator_id = " . $_SESSION['id'] . ") and projects_collab.id = '" . $_GET['id'] . "'";
    }

    $query = mysqli_query($conn, $sql);

    $project_present = mysqli_num_rows($query)>=1;

    if(!$project_present){
      header('Location: ./projects.php');
    }

    while($project = mysqli_fetch_array($query)){

      $sql3 = "SELECT * from users where users.id = " . $project['creator_id'];
      $query3 = mysqli_query($conn, $sql3);
      $creator = mysqli_fetch_array($query3);

      ?>

      <div class="project well" data-project-name="<?php echo $project['name']; ?>">


        <form id="project-<?php echo $project['id']; ?>" onsubmit="validateSubmitProject(<?php echo $project['id']; ?>); return false;" action="actions/projects/modify.php" method="POST">

          <div class="row">
            <div class="col-sm-3">

              <!-- HIDDEN FORM DATA -->
              <input type="hidden" name="project-id" value="<?php echo $project['id']; ?>" />
              <!-- <input type="hidden" name="action" value="update" /> -->
              <!-- /HIDDEN FORM DATA -->

              <?php
              if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
                ?>
                <h4>Public Visibility</h4>
                <input type="radio" onchange="updateCollabDB()" name="visibility" value="1" <?php if($project['public']) echo "checked ";?>> Visible<br>
                <input type="radio" onchange="updateCollabDB()" name="visibility" value="0" <?php if(!$project['public']) echo "checked ";?>> Not visible<br>

                <?php
              }
              ?>

              <h4>Project Stage</h4>
              <select onchange="updateCollabDB()" name="stage">
                <?php
                foreach ($stages as $stage){
                  ?>
                  <option value="<?php echo $stage;?>" <?php if($stage == $project['stage']) echo "selected "; ?>><?php echo $stage;?></option>
                  <?php
                }
                ?>

              </select>

              <h4>Featured Media</h4>
              <select onchange="updateCollabDB(); if(this.value=='external') {document.getElementById('fmedia-<?php echo $project["id"];?>-external').style.display='block';} else{document.getElementById('fmedia-<?php echo $project["id"];?>-external').style.display='none';};" id="project-fmedia-options-<?php echo $project['id'];?>" name="fmedia">
                <option value="unchanged">Keep unchanged</option>
                <option value="NULL">None</option>
                <option value="external">&lt;&lt;External&gt;&gt;</option>

                <?php
                $fmedia_options = preg_grep('~\.(jpeg|jpg|png|gif|bmp|mp4)$~', scandir("../resources/files/projects/" . $project["id"]));

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
                  <option onchange="updateCollabDB()" value="<?php echo $fmedia_option; ?>"><?php echo $fmedia_option; ?></option>
                  <?php
                }
                ?>
              </select>
              <!-- <button type="button" onclick="refreshFeatMedia(<?php echo $project['id'];?>)">Refresh</button> -->

              <input style="display:none;" id='fmedia-<?php echo $project['id'];?>-external' type="text" name="fmedia-external" placeholder='<iframe>...</iframe>' />

              <h4>Team Members</h4>
              <ul class="project-members" id="project-<?php echo $project['id'];?>-members">
                <?php
                $sql2 = "select fname, lname, users.id, username from project_members_collab, users where project_members_collab.user_id = users.id and project_members_collab.project_id = " . $project['id'];
                $query2 = mysqli_query($conn, $sql2);
                while($member = mysqli_fetch_array($query2)){
                  $idSafeUserName = preg_replace("/[^a-zA-Z0-9]/", "", $member['username']);
                  ?>

                  <li id="member-<?php echo $member['username'];?>" class="project-member"><input name="project-member[]" type="hidden" data-name="<?php echo $member["fname"] . " " . $member["lname"]; ?>" value="<?php echo $member['username']; ?>"><?php echo $member["fname"] . " " . $member["lname"]; ?> <button type="button" onclick="removeMemberInList('<?php echo $member["username"]; ?>')">Remove</button></li>

                <?php } ?>
              </ul>
              <ul>
                <li class="project-member">
                  <!-- <input type="text" onkeyup="memberSuggest(<?php echo $project['id']; ?>,this.value)"/> -->
                  <!-- <div id="memberSuggest-<?php echo $project['id']; ?>"></div> -->

                  <div class="autocomplete" style="width:300px;">
                    <input style="width: 100px;" data-name="" id="new-memberSuggest-<?php echo $project['id']; ?>" type="text" onkeyup="memberSuggest(<?php echo $project['id']; ?>,this.value)" autocomplete="off">
                  </div>
                  <!-- <button type=button onclick="addMemberInList(<?php echo $project['id']; ?>)">Add</button> -->
                </li>
              </ul>

              <h4>Tags</h4>
              <ul class="project-tags" id="project-<?php echo $project['id'];?>-tags">
                <?php
                $sql2 = "select tags from projects_collab where id = " . $project['id'];
                $query2 = mysqli_query($conn, $sql2);
                $result2 = mysqli_fetch_array($query2)["tags"];

                $tags = array_filter(explode(",",$result2));


                foreach ($tags as $tag){
                  $idSafeTag = preg_replace("/[^a-zA-Z0-9]/", "", $tag);
                  ?>

                  <li class="project-tag"><input class="project-tag-input" name="project-tag[]" type="hidden" data-tag="<?php echo $tag; ?>" value="<?php echo $tag ?>"><?php echo $tag; ?> <button id="<?php echo $idSafeTag; ?>" onclick="removeTagInList(this, '<?php echo $idSafeTag; ?>');" type="button">Remove</button></li>

                  <?php
                }
                ?>
              </ul>
              <ul>
                <li class="project-tag">
                  <!-- <input type="text" onkeyup="memberSuggest(<?php echo $project['id']; ?>,this.value)"/> -->
                  <!-- <div id="memberSuggest-<?php echo $project['id']; ?>"></div> -->

                  <div class="autocomplete" style="width:300px;">
                    <input style="width: 100px;" data-name="" id="new-tagSuggest-<?php echo $project['id']; ?>" type="text" onkeyup="return; tagSuggest(<?php echo $project['id']; ?>,this.value);" autocomplete="off">
                  </div>
                  <button type=button onclick="addTagInList(<?php echo $project['id']; ?>)">Add</button>
                </li>
              </ul>

            </div>

            <div class="col-sm-9">

              <span class="textbox-label">Project Name</span>
              <h3 class="project-name"><input class="full-width" name="project-name" onchange="updateCollabDB()" value="<?php echo $project['name']; ?>" /></h3>
              <span class="dull">Created on <?php echo date('m/d/Y h:i:s a', strtotime($project['created_on'])); ?> by <?php echo $creator['fname'] . " " . $creator['lname'] . " (@" . $creator['username'] . ")"; ?></span>

              <h4>Brief project information (in under 500 characters)</h4>
              <span class="textbox-label">Visible on all projects page</span>
              <textarea name="summary" maxlength="500" class="summernote project-summary" style="width:100%; height:100%;"><?php echo $project['summary']; ?></textarea>

              <h4>Internal project notes (only for your team)</h4>
              <span class="textbox-label">Visible only to you and your team</span>
              <textarea name="notes" placeholder="todos, contactlist, etc." maxlength="500" class="summernote project-summary" style="width:100%; height:100%;"><?php echo $project['notes']; ?></textarea>

              <h4>Detailed project information space</h4>
              <span class="textbox-label">Visible on this project's page</span>
              <textarea id="editor" name="about" class="summernote project-about" style="width:100%; height:100%;"><?php echo $project['about']; ?></textarea>


            </div>
          </div>

          <br />
          <input type="submit" class="full-width" value="Publish Changes">

        </form>



        <!-- <button type="button" onclick="refreshProjectFiles(<?php echo $project['id'];?>)">Refresh</button> -->

      </div>

      <div class="files-tile">

        <div class="project-files-<?php echo $project['id']; ?>">
          <h4>Project Files</h4>
          <ul id="project-files-list-<?php echo $project['id'];?>">

            <?php
            $dir = "../resources/files/projects/" . $project["id"] . "/";
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
              <li><a href="<?php echo $dir.$file; ?>" /><span class="file-name"><?php echo $file; ?></span></a> <button type="button" onclick="removeFile('<?php echo $file; ?>', <?php echo $project['id']; ?>, this)">Remove</button></li>
              <?php
            }
            ?>
          </ul>
        </div>

        <span class="input-label">Upload a new file</span>

        <form id="project-upload-<?php echo $project['id']; ?>" onsubmit="uploadFile(<?php echo $project['id'];?>, this); return false;" action="./actions/projects/files/ajaxupload.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="project-id" value="<?php echo $project['id']; ?>" />
          <input id="uploadImage-<?php echo $project['id'];?>" type="file" name="image" />
          <!-- <input id="uploadImage-<?php echo $project['id'];?>" type="file" accept="image/*" name="image" /> -->
          <input type="submit" value="Upload">
        </form>

      </div>


    <?php } ?>

  </div>
</div>


<footer>
</footer>

<script>

$(document).ready(function() {
  TogetherJS();
});


//variables
var memberSuggestedName=""

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

var textAreas = document.getElementsByTagName('textarea');

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
  xhr.open("POST", './actions/projects/files/delete.php', true);
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

  xhr.send("project-id="+id+"&file="+file);
}

function refreshProjectFiles(id){
  var listElement = document.getElementById('project-files-list-'+id);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", './actions/projects/files/list.php', true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function() {//Call a function when the state changes.
    if(this.readyState == XMLHttpRequest.DONE && this.status == 200) {
      var files = JSON.parse(xhr.responseText);

      listElement.innerHTML="";

      for (var key in files) {
        var file = files[key];

        listElement.innerHTML = listElement.innerHTML + ("<li><a href='../resources/files/projects/" +id+"/"+file+"' /><span class='file-name'>"+file+"</span></a> <button type='button' onclick='removeFile(\""+file+"\", "+id+", this)'>Remove</button></li>");

      }

      // console.log(xhr.responseText);
    }
  }

  xhr.send("project-id="+id);

}

function refreshFeatMedia(id){

  var optionsList = document.getElementById("project-fmedia-options-"+id);
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
  xhr.open("POST", './actions/projects/files/list.php', true);
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

  xhr.send("project-id="+id);
}

function memberSuggest(id, string){

  // var resultBox = document.getElementById("memberSuggest-"+id);
  if(string=="" || string=="@") {
    // resultBox.innerHTML="";
    return;
  };
  var http = new XMLHttpRequest();
  var url = './aux/memberSuggest.php';
  var params = 'string='+string;
  http.open('POST', url, true);

  //Send the proper header information along with the request
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
      // window.alert(http.responseText);

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
                memberSuggestedName = this.getElementsByTagName("input")[1].value + " " + this.getElementsByTagName("input")[2].value;
                inp.setAttribute("data-name", memberSuggestedName);
                inp.setAttribute("data-username", this.getElementsByTagName("input")[0].value);
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();

                memberClicked(this.getElementsByTagName("input")[0].value, memberSuggestedName);

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

      autocomplete(document.getElementById("new-memberSuggest-"+id), names);
    }
  }
  http.send(params);
}

function validateSubmitProject(id){

  //checking number of members
  // var membersUL = document.getElementById('project-'+id+'-members');
  // if((membersUL.getElementsByTagName('li')).length<1){
  //   window.alert("Please add a member!");
  //   return false;
  // }


  //everything (in the form) seems okay
  var data2send = JSON.stringify($("#project-"+id).serialize());

  data2send=data2send.substring(1, data2send.length-1);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", './actions/projects/modify.php', true);
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

function memberClicked(username, name){

  addMemberInList(<?php echo $_GET['id']; ?>, username, name);

  if (memberClickedFromRemote) {
    return;
  }
  TogetherJS.send({type: "memberClicked", uname: username, fullname: name});

}

function removeMemberInList(member_username){

  // window.alert("removeMemberInList called");
  // window.alert("removeMemberFromList called to remove: "+member_username);

  listElement = document.getElementById("member-"+member_username);
  listElement.remove();

  updateCollabDB();

  if (memberRemovedFromRemote) {
    return;
  }
  TogetherJS.send({type: "memberRemoved", uname: member_username});

}

function addMemberInList(project_id, username="", name=""){

  var finalUsername, finalName;
  var field = document.getElementById('new-memberSuggest-'+project_id);

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

  var membersList = document.getElementById('project-'+project_id+'-members');
  var newListElement = "<li class=\"project-member\" id=\"member-"+finalUsername+"\"><input name=\"project-member[]\" type=\"hidden\" value=\"" +
  finalUsername +
  "\">" +
  finalName +
  " <button class=\"btn btn-primary\" type=\"button\" onclick=\"removeMemberInList('" + finalUsername + "')\">Remove</button></li>";


  membersList.innerHTML = membersList.innerHTML + newListElement;
  field.value="";

  updateCollabDB();

  // if (memberAddedFromRemote) {
  //   return;
  // }
  // TogetherJS.send({type: "memberAdded", uname: finalUsername, name: finalName});

  //<li class="project-member"><input name="project-member[]" type="hidden" value="<?php echo $member['username']; ?>"><?php echo $member["fname"] . " " . $member["lname"]; ?> <button onclick="this.parentNode.parentNode.removeChild(this.parentNode)">Remove</button></li>

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

function addTagInList(project_id, content=""){

  var tagName;

  if(content==""){
    tagName = document.getElementById('new-tagSuggest-'+project_id).value;
  }else{
    tagName = content;
  }

  if(tagName===""){
    return;
  }

  var field = document.getElementById('new-tagSuggest-'+project_id);
  var tagsList = document.getElementById('project-'+project_id+'-tags');
  var idSafeTag = tagName.replace(/[^a-zA-Z]/g, '');
  var newListElement = "<li class=\"project-tag\"><input name=\"project-tag[]\" type=\"hidden\" value=\"" +
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

  //<li class="project-member"><input name="project-member[]" type="hidden" value="<?php echo $member['username']; ?>"><?php echo $member["fname"] . " " . $member["lname"]; ?> <button onclick="this.parentNode.parentNode.removeChild(this.parentNode)">Remove</button></li>
}

function updateCollabDB(){

  // var inputs=document.getElementsByClassName('project-tag-input');
  // var tags = [];
  //
  // for(i=0; i<inputs.length;i++){
  //   tags.push(inputs[i].value);
  // }
  //
  // tags=JSON.stringify(tags);
  // tags=tags.substring(1, tags.length-1)

  var data2send = JSON.stringify($("#project-"+<?php echo $_GET['id']; ?>).serialize());

  // console.log("data for sending:");
  // console.log(data2send);

  data2send=data2send.substring(1, data2send.length-1);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", './actions/projects/aux/update_collab_base.php', true);
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
    url: "./actions/projects/files/ajaxupload.php",
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
        refreshProjectFiles(id);
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

        refreshProjectFiles(<?php echo $_GET['id']; ?>);
        refreshFeatMedia(<?php echo $_GET['id']; ?>);

      } finally {
        filesChangedFromRemote = false;
      }
    });

    TogetherJS.hub.on("memberClicked", function (msg) {
      if (! msg.sameUrl) {
        return;
      }
      // var elementFinder = TogetherJS.require("elementFinder");
      // If the element can't be found this will throw an exception:
      // var element = elementFinder.findElement(msg.element);
      memberClickedFromRemote = true;
      try {
        // MyApp.changeVisibility(element, msg.isVisible);
        // console.log(element);
        // console.log(msg.isVisible);
        // window.alert("clicked");

        addMemberInList(<?php echo $_GET['id']; ?>, msg.uname, msg.fullname);

      } finally {
        memberClickedFromRemote = false;
      }
    });

    TogetherJS.hub.on("memberRemoved", function (msg) {
      if (! msg.sameUrl) {
        return;
      }
      // var elementFinder = TogetherJS.require("elementFinder");
      // If the element can't be found this will throw an exception:
      // var element = elementFinder.findElement(msg.element);
      memberRemovedFromRemote = true;
      try {
        // MyApp.changeVisibility(element, msg.isVisible);
        // console.log(element);
        // console.log(msg.tag);
        // window.alert(msg.uname);

        removeMemberInList(msg.uname);

      } finally {
        memberRemovedFromRemote = false;
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
var memberClickedFromRemote = false;
var memberRemovedFromRemote = false;


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
