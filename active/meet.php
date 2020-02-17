<?php

require_once("../connections/db_connect.php");

  $sql = "SELECT * FROM meeting_rooms WHERE name='Weekly Meeting'";
  $request = mysqli_query($conn, $sql);
  $link = mysqli_fetch_array($request)['link'];

  // echo $link;
  header("Location: $link");

?>
