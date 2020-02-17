<?php
// This is the (public) page to redirect users to either
// - welcome.php to set username and password
// - reset.php to set password
// - login.php if the link is invalid

session_start();
// date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD']=='GET' AND isset($_GET['id']) AND isset($_GET['flag'])){
	require_once("../connections/db_connect.php");

	$sql = "SELECT * FROM users WHERE id='" . $_GET['id'] . "' AND rset_flag='" . $_GET['flag'] . "'";
	// echo $sql;
	$request = mysqli_query($conn,$sql);
	$valid = mysqli_num_rows($request);

	if($valid==0){

		echo "<html><head>";
		echo "<meta type='robots' content='noindex'>";
		echo "<script>window.setTimeout(function(){
        // Move to a new location or you can do something else
        window.location.href = 'https://humanistic.app/active/login.php';
    }, 2000);</script>";
		echo "</head><body><h1>Error: Invalid or used link</h1>Click <a href='https://humanistic.app/active/login.php'>here</a> if you're not redirected to the login page.</body></html>";
		// header('Location:login.php');
	}else{
		$user = mysqli_fetch_array($request);

		$_SESSION['preset_check']=1;
		$_SESSION['preset_id']=$_GET['id'];
		$_SESSION['preset_flag']=$_GET['flag'];
		$_SESSION['preset_action']=$_GET['action'];
		$_SESSION['user'] = $user;
		// echo $_SESSION['user']['name'];
		if($_SESSION['preset_action']=='create'){
			header('Location:welcome.php');
		}else if($_SESSION['preset_action']=='reset'){
			header('Location:reset.php');
		}else if($_SESSION['preset_action']=='signup' && !is_null($user['username'])){
			header('Location:verify.php');
		}else{
			header('Location:login.php');
		}
	}

} else{
	header('Location:login.php');
}

?>
