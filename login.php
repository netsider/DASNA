<?php
session_name("DASNAID");
ini_set('session.hash_function','whirlpool');date_default_timezone_set("America/New_york");
session_start();session_regenerate_id(true); // to prevent session fixation
require_once 'functions.php';
require_once 'vars.php';
$DEBUG = true;
if($_POST['in-submit']){
	if(allgood($_POST)){
		if(isset($_POST['in-user'])){
			$username = $_POST['in-user'];
			$user_set = true;
			$_SESSION['username'] = $_POST['in-user'];
		}elseif(isset($_SESSION['username'])){
			if($DEBUG){ $output .= 'Username set via $_SESSION' . $br;};
			if ($DEBUG){$output .= '<b>Username:</b> ' . $_SESSION['username'] . '<br/>';};
			$username = $_SESSION['username'];
			$user_set = true;
		}else{
			$output .= $fcr . 'Username not set!' . $efcbr;
		}
		if(isset($_POST['in-pass'])){
				$password = $_POST['in-pass'];
				$pass_set = true;
		}else{
			$output .= 'Please enter your password.' . $efcbr;
		}
		if(user_exist($username)){
			$user_exist = true;
			if($pass_set){
				if($DEBUG){$output .= $fcg . 'User exists!' . $efcbr;};
				if(validate($username, $password)){
					if($DEBUG){$output .= $fcg . 'Validation Passed!' . $efcbr;};
					$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
					$_SESSION['authenticated'] = true;
					$authenticated = true; // needed so form hides very first time
					if(save_to_DB($username, 'ipv4', $_SERVER['REMOTE_ADDR'])){
						if($DEBUG){$output .= 'User IP saved to database.' . $br;};
					}else{
						if($DEBUG){$output .= 'User IP NOT saved to database.' . $br;};
					}
				}else{
					if($DEBUG){$output .= $fcr . 'Validation Failed!' . $efcbr;};
				}
			}
		}else{
			$ouput .= $fcr . 'User does not exist!' . $efcbr;
		}
	}
}
$_SESSION['id'] = session_id();
if($_SESSION['authenticated'] === true){
	$authenticated = true;
}else{
	$authenticated = false;
}
if($authenticated === true){
	if($DEBUG){$output .= $fcg . 'User authenticated' . $efcbr;};
}else{
	echo '<center><form action="editor.php" method="POST">';
	require_once 'login-table.php';
	echo '</center>';
}
?>