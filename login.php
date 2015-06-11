<?php
session_start();
require_once('options.php');
require_once('functions.php');
require_once('vars.php');
const debug = true;
$min_length = 1;
$authenticated = false;
$SID = session_id();
if(debug){ echo $fcb . 'Session ID: ' . $SID . $efcbr;};
if($_POST['in-submit']){
	if(allgood($_POST)){
		if(isset($_POST['in-user'])){
			$username = determine_magic_quotes($_POST['in-user']);
			$user_set = true;
			$_SESSION['in-user'] = $username;
		}elseif(isset($_SESSION['in-user'])){
			if(debug){ echo 'Username set via $_SESSION' . $br;};
			$username = $_SESSION['in-user'];
			$user_set = true;
		}else{
			$output .= $fcr . 'Username not set!' . $efcbr;
		}
		if(isset($_POST['in-pass'])){
				$password = determine_magic_quotes($_POST['in-pass']);
				$pass_set = true;
		}else{
			$output .= $fcr . 'Password not set!' . $efcbr;
		}
		if(user_exist($username)){
			$user_exist = true;
			if($pass_set){
				if(debug){$output .= $fcg . 'User exists!' . $efcbr;};
				if(validate($username, $password)){
					if(debug){$output .= $fcg . 'Validation Passed!' . $efcbr;};
					$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
					
					save_to_DB($username, 'ipv4', $ip);
					if(setcookie($username, $cookie_value, time() + (3600), "/")){
						if(debug){ echo $fcg . 'Cookie Set!' . $efcbr;};
					}else{
						if(debug){ echo $fcr . 'Cookie NOT Set!' . $efcbr;};
					}
				}else{
					if(debug){$output .= $fcr . 'Validation Failed!' . $efcbr;};
				}
			}
		}else{
			$ouput .= $fcr . 'User does not exist!' . $efcbr;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login 6</title>
</head>
<body>
	<?php 
	if($authenticated === true){
		
	}else{
		echo "<form action='login.php' method='POST'>";
		require_once 'login-table.php';
	}
	?>
</body>
</html>