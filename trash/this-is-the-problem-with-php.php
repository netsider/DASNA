<?php
error_reporting(E_ALL ^ E_NOTICE);session_start();include_once('options.php');
session_regenerate_id(true);$sid = session_id(); // To prevent session fixation
const database = 'dasna';
	function user_exist($u){
		include('db.php');
		mysqli_select_db($db, database);
		$result = mysqli_query($db, "SELECT name FROM users");
		while($row = mysqli_fetch_array($result)){
			if($row[0] === $u){
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
		include('db.php');
		mysqli_select_db($db, database);
		$query = "SELECT phash FROM users WHERE name = '$username'";
		$result = mysqli_query($db, $query);
			$object = mysqli_fetch_object($result);
			// $array = mysqli_fetch_array($result);
			// echo '<font color="green">' . $array[0] . '</font>';
			echo '<font color="blue">' . $object->phash[0] . '</font>';
			echo '<font color="green">' . $object->phash[1] . '</font>';
			// echo count($object->phash);
			// echo count((array)$object);
			// echo print_r(get_object_vars($object));
		echo '<br/>';
		// var_dump(mysqli_fetch_array($result));
		echo '<pre>';
		var_export($result);
		var_dump($result);
		print_r($result);
		echo '</pre>';
	}else{
		echo 'USER NOT EXIST!';
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
	<table width='10%' border='1'>
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