<?php
// This is the (public) page to login

session_start();
require_once("../connections/db_connect.php");
if (isset($_SESSION['login']) AND $_SESSION['login'] == "success"){
	header('Location: ./overview.php');
}

$status=$status_signup=$username_error=$password_error=$fname_error_signup=$lname_error_signup=$username_error_signup=$email_error_signup=$password_error_signup='';
$error_count=0;

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])){
	//RAW entered data
	$username_temp=$_POST['username'];
	$password_temp=$_POST['password'];

	//Validate form data
	//!preg_match("/^[a-zA-Z]*$/",$_POST['username']) ||
	if (empty($_POST['username']) )
	{ $username_error = "(invalid)"; $error_count++; }
	else { $username = mysqli_real_escape_string($conn,$_POST['username']); }
	//!preg_match("/^[a-zA-Z0-9]*$/",$_POST['username']) ||
	if (empty($_POST['password']) )
	{ $password_error = "(invalid)"; $error_count++; }
	else { $password = hash('sha256', $_POST['password']); }

	//Save form data
	if ($error_count == 0){
		// $status="Processing"
		$usersearch=mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password' and verified='1'");

		if($usersearch){
			$userfound=mysqli_num_rows($usersearch);

			if ($userfound==1){
				$user=mysqli_fetch_array($usersearch);
				$_SESSION['username']=$username;
				$_SESSION['id']=$user['id'];
				$_SESSION['fname']=$user['fname'];
				$_SESSION['lname']=$user['lname'];
				$_SESSION['email']=$user['email'];
				$_SESSION['level']=$user['level'];
				$_SESSION['phone']=$user['phone'];

				$_SESSION['login']="success";

				$sql = "UPDATE users SET last_login=now() WHERE username='" .$username . "'";
				$request = mysqli_query($conn, $sql);

				if ($request && $_SESSION['login'] == "success"){
					if(!empty($_POST['next'])){
						header('Location: ' . $_POST['next']);
						// echo $_POST['next'];
					}else{
						header('Location: ./overview.php');
					}

					exit();
				}else{
					$status="Login failed";
				}

			} else{

				$usersearch=mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
				$unverifieduserfound=mysqli_num_rows($usersearch)>0;

				if($unverifieduserfound){
					$status="Please verify your email!";
				}else{
					$status="Haha, nice try!";
				}
			}
		}

	}


}else if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['reset'])){
	$username_temp=mysqli_real_escape_string($conn,$_POST['username']);
	if (empty($_POST['username']) )
	{ $username_error = "(invalid)"; $error_count++; $status = "Please enter your username";}
	else { $username = mysqli_real_escape_string($conn,$_POST['username']); }

	if ($error_count == 0){
		$status="Processing";
		$usersearch=mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
		if($usersearch){
			$userfound=mysqli_num_rows($usersearch);

			if ($userfound==1){

				$user=mysqli_fetch_array($usersearch);

				$rset_flag = hash('sha256', rand(1,1000));
				$sql = "UPDATE users SET rset_flag='" . $rset_flag . "' WHERE id='" . $user['id'] . "'";
				$request = mysqli_query($conn, $sql);

				require_once "../resources/mail/PHPMailerAutoload.php";
				ini_set('include_path', 'resources');

				$mail = new PHPMailer;

				try {
					$mail->SMTPDebug = 0;                                 // Enable verbose debug output
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = 'smtp.dreamhost.com';                   // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Username = 'no-reply@humanistic.app';                 // SMTP username
					$mail->Password = '';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 587;
					$mail->isHTML(true);

					//Recipients
					$mail->SetFrom('no-reply@humanistic.app', 'Humanistic Co-Design Initiative');
					$mail->addAddress($user['email'], $user['fname'] . " " . $user['lname']);

					$mail->addBCC("records@humanistic.app");


					$linkBase = "http://humanistic.app/active/";
					$linkdata = array(
						'id' => $user['id'],
						'flag' => $rset_flag,
						'action' => "reset"
					);
					$link = $linkBase . "preset.php?" . http_build_query($linkdata);

					// echo $link;

					$mail->Subject = "Reset your HCI password!";
					$mail->Body = "Hey " . $user['fname'] . " " . $user['lname'] . "!<br />Password reset link was requested for your <a href='https://humanistic.app/'>Humanistic Co-Design Initiative</a> account.<br />Click <a href='" . $link . "'>here</a> (" . $link . ") to reset your password.<br />It is safe to ignore this mail if you didn't request password reset.<br /><br />Warm regards,<br />HCI Community";
						$mail->AltBody = "Hey " . $user['fname'] . " " . $user['lname'] . ", password reset link was requested for your <a href='https://humanistic.app/'>Humanistic Co-Design Initiative</a> account.<br />Use this link (" . $link . ") to reset password.<br />It is safe to ignore this mail if you didn't request password reset.<br /><br />Warm regards,<br />HCI Community";

							$mail->send();
							$status = "Check your mailbox for reset link!";
						} catch (Exception $e) {
							$status = "There was an error processing your request. Error code: EEMA".$mail->ErrorInfo;
						}

					} else{
						$status="Haha, nice try!";
					}

				}

			}


		}else if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['signup'])){
			//user wants to sign up

			$password1=hash('sha256', $_POST['password1']); //removing sql injection attempt
			$password2=hash('sha256', $_POST['password2']); //and hashing password

			$username = $_POST['username'];
			$email = $_POST['email'];
			$fname = mysqli_real_escape_string($conn, trim($_POST['fname']));
			$lname = mysqli_real_escape_string($conn, trim($_POST['lname']));

			if(strlen($_POST['password1'])<8){
				$password_error_signup = "Password length needs to be greater than 8";
				$error_count++;
			}
			if(trim($fname)==""){
				$fname_error_signup = "Of course you have a name";
				$error_count++;
			}
			if($password1!=$password2){
				$password_error_signup = "Repeated password needs to match";
				$error_count++;
			}
			if(strlen($_POST['username'])<5){
				$username_error_signup = "Username needs atleast 5 characters";
				$error_count++;
			}
			if(strstr($username,' ')){
				$username_error_signup = "Username can not contain a blankspace";
				$error_count++;
			}
			if(preg_match("/DROP/i",$username) OR preg_match("/DELETE/i",$username)){
				$username_error_signup = "Invalid username";
				$error_count++;
			}
			if(strpos($username, '%') !== false){
				$username_error_signup = "Invalid username";
				$error_count++;
			}
			if(strpos($username, '\'') !== false){
				$username_error_signup = "Invalid username";
				$error_count++;
			}
			if(strpos($username, '\"') !== false){
				$username_error_signup = "Invalid username";
				$error_count++;
			}
			if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬\-\"]/', $username))
				{
					$username_error_signup = "Invalid username";
					$error_count++;
				}

				$sql = "SELECT * from users WHERE username='" . $username . "'";
				$request=mysqli_query($conn,$sql);
				$valid = mysqli_num_rows($request)==0;
				if(!$valid){
					$username_error_signup = "Username has been used";
					$error_count++;
				}

				$sql = "SELECT * from users WHERE email='" . $email . "'";
				$request=mysqli_query($conn,$sql);
				$valid = mysqli_num_rows($request)==0;
				if(!$valid){
					$email_error_signup = "Email has been used";
					$error_count++;
				}

				// $allowed = ['gmail.com','yahoo.com'];
				// if (filter_var($email, FILTER_VALIDATE_EMAIL))
				// {
				//   $parts = explode('@', $email);
				//   $domain = array_pop($parts);
				//   if ( ! in_array($domain, $allowed))
				//   {
				//     $email_error_signup = "University email required";
				//     $error_count++;
				//   }
				// }

				if($error_count==0){
					//form values valid

					$rset_flag = hash('sha256', rand(1,10000));
					$sql = "INSERT INTO users (fname, lname, email, level, rset_flag, accepted, password, username, whereabouts) VALUES ('" . $fname . "','" . $lname . "','" . $email . "','member','" . "$rset_flag" . "', '1', '" . $password1 . "', '" . $username . "', 'Amazing Earth')" ;
					$request = mysqli_query($conn, $sql);

					$sql = "SELECT * FROM users WHERE email='" . $email . "'";
					$request = mysqli_query($conn, $sql);
					$newPerson = mysqli_fetch_array($request);

					require_once "../resources/mail/PHPMailerAutoload.php";
					ini_set('include_path', 'resources');
					$mail = new PHPMailer;

					try {
						$mail->SMTPDebug = 0;                                 // Enable verbose debug output
						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp.dreamhost.com';                   // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'no-reply@humanistic.app';                 // SMTP username
						$mail->Password = '';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;
						$mail->isHTML(true);

						//Recipients
						$mail->SetFrom('no-reply@humanistic.app', 'Humanistic Co-Design Initiative');
						$mail->addAddress($newPerson['email'], $newPerson['fname'] . " " . $newPerson['lname']);

						$mail->addBCC("records@humanistic.app");

						$linkBase = "http://humanistic.app/active/";
						$linkdata = array(
							'id' => $newPerson['id'],
							'flag' => $rset_flag,
							'action' => "signup"
						);
						$link = $linkBase . "preset.php?" . http_build_query($linkdata);

						// echo $link;

						$mail->Subject = "Welcome to HCI!";
						$mail->Body = "Hey " . $newPerson['fname'] . " " . $newPerson['lname'] . "!<br />Humanistic Co-Design Initiative community welcomes you!<br />Click <a href='" . $link . "' />here</a> (" . $link . ") to set your username and password.<br /><br />Warm regards,<br />HCI Community";
						$mail->AltBody = "Hey " . $newPerson['fname'] . " " . $newPerson['lname'] . "! Humanistic Co-Design Initiative community welcomes you! Use this link (" . $link . ") to set your username and password. Warm regards, HCI Community";

						$mail->send();
						$status_signup = "Hooray, click on the verification link sent to email to get started!";
					} catch (Exception $e) {
						$status = "There was an error processing your request. Error code: EEMA".$mail->ErrorInfo;
					}

				}else{
					$fname_temp_signup=$_POST['fname'];
					$lname_temp_signup=$_POST['lname'];
					$email_temp_signup=$_POST['email'];
					$username_temp_signup=$_POST['username'];
					$password1_temp_signup=$_POST['password1'];
					$password2_temp_signup=$_POST['password2'];
				}


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


			<title>Humanistic Core - Login</title>
			<meta charset="UTF-8">
			<meta type="robots" content="noindex">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<meta name="description" content="">

			<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="../resources/css/common.css">
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
								<li><a href="../index.php">Home</a></li>
								<li><a href="../projects.php">Projects</a></li>
								<li><a href="../workshops.php">Workshops</a></li>
								<li><a href="../about.php">About</a></li>
							</ul>

							<ul class="nav navbar-nav navbar-right">
								<!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
								<li class="active"><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
							</ul>

						</div>
					</div>
				</nav>
			</header>

			<!-- MAIN CONTENT -->

			<div class="container">

				<!-- LOGIN FORM -->
				<div id="login-container">
					<h3>Login in</h3>
					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">

						<input type="hidden" name="next" value="<?php if(isset($_GET['next'])) echo $_GET['next']; ?>" />

						<span class="textbox-label">Username</span>
						<span class="input_error" ><?php echo $username_error; ?></span>
						<input required type="text" name="username" maxlength="100" placeholder="Required" value="" /><br />

						<span class="textbox-label">Password</span>
						<span class="input_error"><?php echo $password_error; ?></span>
						<input type="password" name="password" maxlength="100" placeholder="Required" value="" /><br />

						<input type="submit" name="login" value="Log in"/>
						<input type="submit" name="reset" value="Forgot Password"/>
						<?php echo $status; ?>

					</form>
				</div>


				<div id="signup-container">
					<h3>Sign up</h3>

					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">

						<span class="textbox-label">First Name</span>
						<span class="input_error" ><?php if($fname_error_signup!=="") echo $fname_error_signup . "<br />"; ?></span>
						<input type="text" name="fname" maxlength="100" placeholder="Louis" value="<?php if(isset($fname_temp_signup) ){ echo $fname_temp_signup; } else {echo '';} ?>" /><br />

						<span class="textbox-label">Last Name</span>
						<span class="input_error" ><?php if($lname_error_signup!=="") echo $lname_error_signup . "<br />";; ?></span>
						<input type="text" name="lname" maxlength="100" placeholder="Braille" value="<?php if(isset($lname_temp_signup) ){ echo $lname_temp_signup; } else {echo '';} ?>" /><br />

						<span class="textbox-label">Username</span>
						<span class="input_error" ><?php if($username_error_signup!=="") echo $username_error_signup . "<br />";; ?></span>
						<input type="text" name="username" maxlength="100" placeholder="louisbraille" value="<?php if(isset($username_temp_signup) ){ echo $username_temp_signup; } else {echo '';} ?>" /><br />

						<span class="textbox-label">Email</span>
						<span class="input_error" ><?php if($email_error_signup!=="") echo $email_error_signup . "<br />";; ?></span>
						<input type="email" name="email" maxlength="100" placeholder="louis@email.com" value="<?php if(isset($email_temp_signup) ){ echo $email_temp_signup; } else {echo '';} ?>" required /><br />

						<span class="textbox-label">Password</span>
						<span class="input_error"><?php if($password_error_signup!=="") echo $password_error_signup . "<br />";; ?></span>
						<input type="password" name="password1" maxlength="100" placeholder="" value="<?php if(isset($password1_temp_signup) ){ echo $password1_temp_signup; } else {echo '';} ?>" /><br />

						<span class="textbox-label">Confirm Password</span>
						<input type="password" name="password2" maxlength="100" placeholder="" value="<?php if(isset($password1_temp_signup) ){ echo $password2_temp_signup; } else {echo '';} ?>" /><br />

						<input type="submit" name="signup" value="Sign Up"/>
						<?php echo $status_signup; ?>

					</form>
				</div>

				<footer>
				</footer>

			</body>
			</html>
