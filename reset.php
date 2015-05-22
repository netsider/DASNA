<?php
error_reporting(E_ALL ^ E_NOTICE);session_start();include_once('options.php');
session_regenerate_id(true);$sid = session_id(); // To prevent session fixation
const database = 'dasna';
$text = "<tr><td>Password:</td><td><input type='password' name='in-pass' /></td></tr>";
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
		// echo 'User exists!';
		include('db.php');
		mysqli_select_db($db, database);
		$query = "SELECT phash FROM users WHERE name = '$username'";
		$result = mysqli_query($db, $query);
			// $object = mysqli_fetch_object($result);
			$array = mysqli_fetch_array($result);
			echo '<font color="green">' . $array[0] . '</font><br/>';
			echo strlen($array[0]);
			if($array[0] === NULL){
				// echo 'NULL!<br/>';
				$pfp = true;
				$output = '<font color="green"><b>Please type an alphanumeric password to continue</font></b>';
			}else{
				$output = '<font color="red"><b>Username already has password/value!</font></b>';
				$pfp = false;
			}
		// echo '<br/>';
		// echo '<pre>';
		// var_dump($result);
		// print_r($result);
		// echo '</pre>';
	}else{
		$output = '<b>User does not exist<b>!';
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
	<table width='25%' border='1'>
	<tr><td colspan="2"><center>Set Password</center></td></tr>
	<form action='reset.php' method='POST'>
	<tr><td>Username:</td><td><input type='text' name='in-user' <?php 
	if($pfp){ echo "value='$username' disabled";};?> /></td></tr>
	<?php 
	if($pfp){
	echo $text;};?>
	<tr><td colspan='2'><input type='submit' name='in-submit' value='Login' /><?php if(isset($output)){
	echo $output;}; ?></td></tr>
	</form>
	</table>
	</center>
</body>
</html>