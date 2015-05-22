<?php
session_start();include_once('options.php');
session_regenerate_id(true);$sid = session_id(); // To prevent session fixation
	function user_exist($user){
		include_once('db.php');
		mysqli_select_db($db, $database);
		$result = mysqli_query($db, "SELECT name FROM users");
		while($row = mysqli_fetch_array($result)){
			if($row[0] === $username){
				echo 'User <b>' . $username . '</b> found!';
				return true;
			}
		}
		mysqli_close($db);
		return false;
	};
if($_POST){
	$username = $_POST['in-user'];
	$password = $_POST['in-pass'];
	if(user_exist($username)){
		echo 'User exists!';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	<center>
	<table width='20%' border='1'>
	<tr><td colspan="2"><?php echo $sid; ?></td></tr>
	<form action='reset.php' method='POST'>
	<tr><td>Username:</td><td><input type='text' name='in-user' /></td></tr>
	<tr><td>Password:</td><td><input type='password' name='in-pass' /></td></tr>
	<tr><td colspan='2'><input type='submit' name='in-submit' value='Login' /></td></tr>
	</form>
	</table>
	</center>
</body>
</html>