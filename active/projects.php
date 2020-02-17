<?php
// This is the (private) page to display all project list (with a link to edit)

session_start();
if($_SESSION['login'] != "success"){
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


<title>Humanistic Core - Projects</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="../resources/js/masonry.pkgd.min.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../resources/css/common.css">
<link rel="stylesheet" type="text/css" href="../resources/css/projects.css">

<!-- <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script> -->

<!-- include libraries(jQuery, bootstrap) -->
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>

<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>

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
            <li><a href="console.php"><span class="glyphicon glyphicon-cog"></span> Console</a></li>
          <?php }?>
            <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>Sign Up</a></li> -->
            <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['fname']; ?></a></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
          </ul>

        </div>
      </div>
    </nav>
  </header>

  <div class="container">

    <input class="full-width search-bar" id="project-search-string" oninput="projectSearch(this.value)" type="text" placeholder="Search projects" /><br /><br />


    <button class="new-light" onclick="document.getElementById('new-project').style.display='inline-block'; this.style.display='none'; ">New project</button>
    <div id="new-project">
      <form action="./actions/projects/create.php" method="POST">
        <span class="input-label">Project Name</span><input style="padding: 5px;" type="text" name="project-name" value="" />
        <input class="new-submit" type="submit" value="Create" />
      </form>
    </div><br /><br />

    <?php if(isset($_SESSION['errors']['project-creation'])) {echo $_SESSION['errors']['project-creation']; unset($_SESSION['errors']['project-creation']);} ?>

    <div id="projects">
      <?php

      $sql = "SELECT * from project_stages order by sequence_number ASC";
      $query = mysqli_query($conn, $sql);
      $stages = array();
      while($stage = mysqli_fetch_array($query)) array_push($stages,$stage['name']);

      if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
        $sql = "SELECT * FROM `projects` ORDER BY `projects`.`created_on` DESC";
      }else{
        //if the user is a community member
        $sql="SELECT * FROM `projects` WHERE (projects.id IN (SELECT project_members.project_id FROM `project_members` WHERE project_members.user_id=" . $_SESSION['id'] . ")) or projects.creator_id = " . $_SESSION['id'] . " ORDER BY `projects`.`created_on` DESC";
      }

      $query = mysqli_query($conn, $sql);

      while($project = mysqli_fetch_array($query)){

        $sql3 = "SELECT * from users where users.id = " . $project['creator_id'];
        $query3 = mysqli_query($conn, $sql3);
        $creator = mysqli_fetch_array($query3);

        ?>

        <div class="well project" data-project-name="<?php echo $project['name']; ?>">

          <!-- <span><?php echo $project['id']; ?></span> -->

          <h3 class="project-name"><?php echo $project['name']; ?></h3>
          Created on <?php echo date('m/d/Y h:i:s a', strtotime($project['created_on'])); ?> by <?php echo $creator['fname'] . " " . $creator['lname'] . " (" . $creator['username'] . ")"; ?><br />

          <button onclick="window.location='project.php?id=<?php echo $project['id'] . "#" . $project['id']; ?>'">Edit Project</button>

          <?php
          if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
            ?>
            <h4>Public Visibility</h4>
            <?php
            if($project['public']) {
              echo "Visible";
            }else{
              echo "Not visible";
            }
            ?>

            <?php
          }
          ?>

          <h4>Project Stage</h4>
          <?php echo $project['stage']; ?>

          <h4>Team Members</h4>
          <?php
          $sql2 = "select fname, lname, users.id, username from project_members, users where project_members.user_id = users.id and project_members.project_id = " . $project['id'];
          $query2 = mysqli_query($conn, $sql2);

          if(mysqli_num_rows($query2)==0){
            ?>
            None
            <?php
          }else{ ?>

            <ul class="project-members" id="project-<?php echo $project['id'];?>-members">

              <?php

              while($member = mysqli_fetch_array($query2)){
                $linkdata = array(
                  'username' => $member['username']
                );
                ?>

                <li class="project-member"><a target="_blank" href="./profile.php?<?php echo http_build_query($linkdata);?>"><?php echo $member["fname"] . " " . $member["lname"]; ?> (@<?php echo $member["username"];?>)</a></li>

              <?php } ?>
            </ul>
          <?php } ?>

          <h4>Tags</h4>
          <ul class="project-tags" id="project-<?php echo $project['id'];?>-tags">
            <?php
            $sql2 = "select tags from projects where id = " . $project['id'];
            $query2 = mysqli_query($conn, $sql2);
            $result2 = mysqli_fetch_array($query2)["tags"];

            $tags = array_filter(explode(",",$result2));

            foreach ($tags as $tag){?>

              <li class="project-tag"><?php echo $tag; ?></li>

            <?php } ?>
          </ul>

          <!-- <div class="project-about">
          <?php echo $project['about']; ?>
        </div> -->

        <h4>Summary</h4>
        <?php echo $project['summary']; ?>

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
              <li><a href="<?php echo $dir.$file; ?>" /><span class="file-name"><?php echo $file; ?></span></a></li>
              <?php
            }
            ?>
          </ul>
        </div>

        <!-- <button type="button" onclick="refreshProjectFiles(<?php echo $project['id'];?>)">Refresh</button> -->

      </div>

    <?php } ?>

  </div>

</div>

<div class="container" id="recruitment-wall">
  <h3 style="color: #eee">Other projects that need your help</h3>

  <div class="grid-recruitment-wall">

    <div class="grid-item-recruitment-wall">
      <div class="available_position">
        <h4>New posting</h4>
        <form action="./actions/recruitment/new_post.php" method="POST">
          <span class='input-label'>Project</span>
          <select name="project-id" required>
            <?php
            if($_SESSION['level']=="admin" || $_SESSION['level']=="volunteer"){
              $sql = "SELECT * FROM `projects` ORDER BY `projects`.`created_on` DESC";
            }else{
              //if the user is a community member
              $sql="SELECT * FROM `projects` WHERE (projects.id IN (SELECT project_members.project_id FROM `project_members` WHERE project_members.user_id=" . $_SESSION['id'] . ")) or projects.creator_id = " . $_SESSION['id'] . " ORDER BY `projects`.`created_on` DESC";
            }
            $query = mysqli_query($conn, $sql);
            while($project = mysqli_fetch_array($query)){
              ?>
              <option value="<?php echo $project['id']; ?>"><?php echo $project['name']; ?></option>
              <?php
            }
            ?>
          </select>
          <span class='input-label'>Role</span>
          <input name="role" type="text" placeholder="Electrical Engineer" required /><br />
          <span class='input-label'>Responsibilities</span>
          <textarea name="responsibilities" type="text" required placeholder="Help improve efficiency of the electrical circuits"></textarea>

          <span class='input-label'>Eligibility</span>
          <select name="eligibility">
            <option value="0" selected>Anyone</option>
            <option value="1">Co-Designers only</option>
          </select>

          <br />
          <br />

          <input type='submit' name='recruitment' value='Post'/>

          <span><?php if(isset($_SESSION['messages']['new-recruitment'])) {echo $_SESSION['messages']['new-recruitment']; unset($_SESSION['messages']['new-recruitment']);} ?></span>
        </form>
      </div>
    </div>

    <?php
    $sql = "SELECT * FROM projects, project_recruitment where project_recruitment.project_id = projects.id";
    $query = mysqli_query($conn, $sql);
    while($posting = mysqli_fetch_array($query)){
      ?>

      <div class="grid-item-recruitment-wall">
        <div class="available_position">
          <h4><?php echo $posting['role']; ?></h4>
          for <span class="positing-project"><?php echo $posting['name']; ?></span><br />
          <?php echo $posting['summary']; ?>
          <h5>Responsibilities</h5>
          <?php echo $posting['responsibilities']; ?>

          <h5>Contact the team</h5>
          <?php
          $poster_team_member = 0;
          $sql2 = "SELECT * FROM users where users.id in (SELECT project_members.user_id FROM project_members where project_members.project_id='" . $posting['project_id'] . "')";
          $query2 = mysqli_query($conn, $sql2);
          while($team_member = mysqli_fetch_array($query2)){
            $linkdata = array(
              'username' => $team_member['username']
            );
            if($team_member['username']==$_SESSION['username']) $poster_team_member=1;
            ?>
            <li><a target="_blank" href="./profile.php?<?php echo http_build_query($linkdata);?>"><?php echo $team_member['fname'] . " " . $team_member['lname'] . " (@" . $team_member['username'] . ")"; ?></a></li>
          <?php }
          ?>

          <?php if($poster_team_member){
            ?>
            <br />
            <form onsubmit="return confirm('Are you sure?')" action="./actions/recruitment/remove_post.php" method="POST">
              <input type='hidden' name='posting_id' value='<?php echo $posting['id']; ?>'/>
              <input type='submit' name='recruitment' value='Remove posting'/>

              <span><?php if(isset($_SESSION['messages']['delete-recruitment'])) {echo $_SESSION['messages']['delete-recruitment']; unset($_SESSION['messages']['delete-recruitment']);} ?></span>

            </form>
          <?php } ?>

        </div>
      </div>
      <?php
    }
    ?>

  </div>

</div>

<footer>
</footer>


<script>

</script>

<script>

function projectSearch(string) {
  // Declare variables
  var input, filter, projectsDiv, projects, name, i, txtValue;
  filter = string.toUpperCase();
  projectsDiv = document.getElementById("projects");
  projects = projectsDiv.getElementsByClassName('project');

  // Loop through all list items, and hide those who don't match the search query
  for (var i = 0; i < projects.length; i++) {
    // name = projects[i].getElementsByClassName("project-name")[0].getElementsByTagName("input")[0];
    // name2 = projects[i].getElementsByTagName("textarea")[0];
    // txtValue = name.value + name2.innerHTML;

    txtValue=projects[i].innerText;
    console.log(txtValue);

    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      projects[i].style.display = "";
    } else {
      projects[i].style.display = "none";
    }
  }

}

var elem = document.querySelector('.grid-recruitment-wall');
var msnry = new Masonry( elem, {
  // options
  itemSelector: '.grid-item-recruitment-wall',
  columnWidth: 350,
});
</script>
<!-- <script src="https://togetherjs.com/togetherjs-min.js"></script> -->
</body>
</html>
