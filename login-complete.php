<?php
session_start();
require_once('options.php');
require_once('functions.php');
require_once('vars.php');
session_regenerate_id(true);
$sid = session_id(); // To prevent session fixation
const debug = true;
if($_POST['in-submit']){ // If submit button has been pressed
	if (allgood($_POST)){
		if(isset($_POST['in-user'])){
			$username = determine_magic_quotes($_POST['in-user']);
		}
		if(isset($_POST['in-pass'])){
			$password = determine_magic_quotes($_POST['in-pass']);
		}
		if(user_exist($username)){
			if(debug){$output .= 'User exists!' . $efcbr;};
			if($hash_fromDB = read_hash($username)){
					if(debug){$output .= $fcg . 'Hash from Database: ' . $hash_fromDB . $efcbr;};
			}else{
					if(debug){$output .= $fcr . 'Reading hash from database failed!' . $efcbr;};
			}
			if($salt_fromDB = read_salt($username)){
					if(debug){$output .= $fcg . 'Salt from Database: ' . $salt_fromDB . $efcbr;};
			}else{
					if(debug){$output .= $fcr . 'Reading salt from database failed!' . $efcbr;};
			}
			$iterations = 100000;
			$final_hash = create_hash($hash = create_hash($password, $salt_fromDB, 'ripemd320', $iterations), $salt_fromDB, 'whirlpool', $iterations);
			if(debug){$output .= '<b>Final Hash Generated: ' . $final_hash . '</b><br/> Salt(from DB): ' . $salt_fromDB . $efcbr;};
			if(hash_equals($hash_fromDB, $final_hash)){ // To prevent timing attacks
				if(debug){echo $fcg . 'Hashes Match!' . $efcbr;};
			}else{
				if(debug){echo $fcr . 'Hashes do NOT match!' . $efcbr;};
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
	<title>Login</title>
</head>
<body>
	<table width='20%' border='1'>
	<tr><td colspan="2">Login</td></tr>
	<form action='login.php' method='POST'>
	<tr><td>Username:</td><td><input type='text' name='in-user' /></td></tr>
	<tr><td>Password:</td><td><input type='password' name='in-pass' /></td></tr>
	<tr><td colspan='2'><input type='submit' name='in-submit' value='Login' /></td></tr>
	<tr><td colspan='2'><?php if(isset($output)){ echo $output;}; ?></td></tr>
	</form>
	</table>
	

</body>
</html>