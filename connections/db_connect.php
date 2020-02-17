<?php
			//Connect to MySQL Database
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}

			$db_host='localhost';
			$db_username='root';
			$db_password='';
			$db_name='humanisticapp';

			$conn = mysqli_connect($db_host,$db_username,$db_password,$db_name) or die("Connection error: " . mysqli_connect_error());

			if($conn){
//                echo "Connected";
				$_SESSION['db_connection_status'] = 1;
			} else{
//                echo "Connection failed";
				$_SESSION['db_connection_status'] = 0;
			}

			// echo "<!--Successful connection-->";
?>
