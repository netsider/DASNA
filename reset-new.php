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
	function phash_null($u){
		include('db.php');
		mysqli_select_db($db, database);
		$query = "SELECT phash FROM users WHERE name = '$u'";
		$result = mysqli_query($db, $query);
		$array = mysqli_fetch_array($result);
		$plength = strlen($array[0]);
		$phash = $array[0];
		echo '<font color="green">' . $phash . '</font><br/>';
		echo '<font color="blue">' . $plength . '</font><br/>';
		if($array[0] === NULL){
				return true;
			}else{
				return false;
		}
		return false;
	};
if($_POST['in-submit']){ // executed everytime
	$username = $_POST['in-user'];
	if(user_exist($username)){
		echo 'User exists!';
		$password = $_POST['in-pass'];
		if(phash_null($username) === true){
			$output = '<font color="green"><b>Type an alphanumeric password to be your password.</font></b>';
			$null = true;
			echo 'PHASH is NULL!';
		}else{
			$output = '<font color="red"><b>Username not found, or already contains password.</font></b>';
			$null = false;
		}
	}else{
		$output = '<b>User does not exist<b>!';
	}
	if($null){ // executed on third reload
		if(isset($_POST['in-user']) && isset($_POST['in-pass'])){
			$plength = strlen($_POST['in-pass']);
			$ulength = strlen($_POST['in-user']);
			if($plength > 0){
				// if passwork not blank
				$set = true;
				$output = 'BOTH SET!';
			}else{
				$output = '';	
			}
		}
	}
	if($_POST['in-submit'] === "Confirm Password"){ // executed on fourth reload
	$set = true;
		$output = 'Password Confirmed!';
		echo '<pre>';
	print_r($_POST);
	echo '</pre><br/>';
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
	<form action='reset-new.php' method='POST'>
	<tr><td>Username:</td><td><input type='text' name='in-user' <?php 
	if(isset($username)){ echo "value='$username'";}; if(isset($username)){ echo 'disabled';};?>
	
	<?php 
	echo "/></td></tr>";
	if($null){
	echo "<tr><td><b>New Password</b>:</td><td><input type='password' name='in-pass'";
	if(isset($password)){
		echo "value='$password'";
		echo ' disabled';
	}
	echo "/></td></tr>";
	};
	if(isset($username)){
		echo "<input type='hidden' name='in-user' value='$username'>";
	}
	if(isset($password)){
		echo "<input type='hidden' name='in-pass' value='$password'>";
	}
	if($set){
		echo "<tr><td><b>Re-type Password</b>:</td><td><input type='text' name='in-pass-new'";
		if(isset($_POST['in-pass-new'])){
			$newpass = $_POST['in-pass-new'];
			echo "value='$newpass'";
			echo ' disabled';
		}
		echo '/></td></tr>';
	}
	?>
	<tr><td colspan='2'><input type='submit' name='in-submit' <?php if($null){ echo "value='Confirm Password'"; }else{ echo'Submit'; } ?> /><?php if(isset($output)){
	echo $output;}; ?></td></tr>
	</center><br/>
	<?php echo '</form></table>'; ?>
</body>
</html>