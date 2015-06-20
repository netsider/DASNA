<?php
error_reporting(E_ALL ^ E_NOTICE);
session_name("DASNAID");
ini_set('session.hash_function','whirlpool');
date_default_timezone_set("America/New_york");
session_start();session_regenerate_id(true); // to prevent session fixation
require_once 'functions.php';
require_once 'vars.php';
const debug = true;
$_SESSION['id'] = session_id();
if(debug){ echo '$_SESSION[id]: ' . $_SESSION['id'] . '<br/>';};
if($_SESSION['authenticated'] === true){
	$expires = 3600 * 24 * 365 * 5; // 5 years
	setrawcookie('DCOUNT', $_SESSION['count'], time() + ($expires), "/");
	$authenticated = true;
}else{
	$authenticated = false;
}
if(empty($_SESSION['count'])){
	if(!empty($_COOKIE['DCOUNT'])){
		$_SESSION['count'] = $_COOKIE['DCOUNT'];
	}else{
		$_SESSION['count'] = 1;
	}
}else{
	$_SESSION['count']++;
}
if($_POST['in-submit']){
	if(allgood($_POST)){
		if(isset($_POST['in-user'])){
			$username = $_POST['in-user'];
			$user_set = true;
			$_SESSION['username'] = $_POST['in-user'];
		}elseif(isset($_SESSION['username'])){
			if(debug){ echo 'Username set via $_SESSION' . $br;};
			$username = $_SESSION['username'];
			$user_set = true;
		}else{
			$output .= $fcr . 'Username not set.' . $efcbr;
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
				if(debug){$output .= $fcg . 'User exists!' . $efcbr;};
				if(validate($username, $password)){
					if(debug){$output .= $fcg . 'Validation Passed!' . $efcbr;};
					$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
					$_SESSION['authenticated'] = true;
					$authenticated = true; // needed so form hides very first time
					if(save_to_DB($username, 'ipv4', $_SERVER['REMOTE_ADDR'])){
						if(debug){echo 'User IP saved to database.' . $br;};
					}else{
						if(debug){echo 'User IP NOT saved to database.' . $br;};
					}
					// if(save_to_DB($username, 'sid', $_SESSION['id'])){
						// if(debug){echo 'User SID saved to database.' . $br;};
					// }else{
						// if(debug){echo 'User SID NOT saved to database.' . $br;};
					// }
				}else{
					if(debug){$output .= $fcr . 'Validation Failed!' . $efcbr;};
					// $_SESSION['authenticated'] = false;
				}
			}
		}else{
			$ouput .= $fcr . 'User does not exist!' . $efcbr;
		}
	}
}
if($authenticated === true){
	if(debug){echo $fcg . 'User authenticated' . $efcbr;};
}else{
	echo '<center><form action="editor.php" method="POST">';
	require_once 'login-table.php';
	echo '</center>';
}
?>