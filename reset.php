<?php
ini_set("sendmail_path","/usr/sbin/sendmail");
include_once('options.php');
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
		echo '<font color="green">Field Value: "' . $phash . '"</font><br/>';
		echo '<font color="blue">Length: ' . $plength . '</font><br/>';
		// if($array[0] === NULL){
			// return true;
		// }
		if($phash === NULL){
			return true;
		}
		return false;
	};
	function allgood($array){ // returns false if not alphanumeric
		$alpha = true;
		$fc = '<font color="red">';
		$efc = '</font><br/>';
		foreach($array as $key => $value){
			if (ctype_alnum($value)) {
				echo '<font color="green">The field (<b>' . $key . '</b>) is completely alphanumeric.' . $efc;
			} else {
				$alpha = false;
				if(empty($value)){
					echo $fc . '<b>Field Empty!</b>' . $efc;
				}else{
					echo $fc . 'The field (<b>' . $key . '</b>) is not completely alphanumeric.' . $efc;
				}
			}
		}
		if($alpha){
			return true;
		}else{
			echo '<pre>';
			print_r($_POST);
			echo '</pre><br/>';
			return false;
		}
	};
if($_POST['in-submit']){
	if(allgood($_POST)){ 
		$username = $_POST['in-user'];
		if(isset($_POST['in-pass'])){
			$password = $_POST['in-pass'];
		}
		if(user_exist($username)){
			if(phash_null($username) === true){
				$output = '<font color="green"><b>Type an alphanumeric password to be your password.</font></b>';
				$null = true;
			}else{
				$output = '<font color="red"><b>Username not found, or not NULL!</font></b>';
				$null = false;
			}
		}else{
			$output = '<b>User does not exist<b>!';
		}
	}
	if($null){
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
		if($_POST['in-submit'] === "Confirm" && isset($_POST['in-phone'])){ // executed when all critera met
			// $set = true;
		echo '<pre>';
		print_r($_POST);
		echo '</pre><br/>';
		$output = 'Password Set!';
		$msg = 'First line of text';
		$msg = wordwrap($msg,70);
		$headers = 'From:mailserver@dasna.net';
		mail("rdoubleoc@aol.com","My subject",$msg,$headers);
		}
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
	if(isset($username)){ echo "value='$username'";}; 
	if(isset($username)){ 
		echo 'disabled';
	}
	echo "/></td></tr>";
	if($null){
	echo "<tr><td><b>New Password</b>:</td><td><input type='password' name='in-pass'";
	if(isset($password)){
		echo "value='$password'";
		echo ' disabled';
	}
	echo "/></td></tr>";
	}
	if($set){
		echo "<tr><td><b>Re-type Password</b>:</td><td><input type='password' name='in-pass-new'";
		if(isset($_POST['in-pass-new'])){
			$newpass = $_POST['in-pass-new'];
			echo "value='$newpass'";
			echo ' disabled';
		}
		echo "/></td></tr>";
		echo '<tr><td>Phone Number:</td><td><input type="text" name="in-phone" length="10"/></td></tr>';
		// echo "<tr><td><b>Security Question #1:</b>:</td><td><b>Security Question #2:</b>:</td></tr>";
		// echo "<tr><td>";
		// echo '<select name="in-ques-1">';
		// echo '<option value="favfood">Favorite Food</option><option value="favbook">Favorite Book</option>';
		// echo "</select>";
		// echo "</td><td>";
		// echo '<select name="in-ques-2">';
		// echo '<option value="birthplace">Birthplace</option><option value="firstlove">First Girlfriend/Boyfriend</option>';
		// echo "</select>";
		// echo "</td></tr>";
		// echo "<tr><td><input type='text' name='in-ans-1'/></td><td><input type='text' name='in-ans-2'/></td></tr>";
	}
	if(isset($username)){
		echo "<input type='hidden' name='in-user' value='$username'>";
	}
	if(isset($password)){
		echo "<input type='hidden' name='in-pass' value='$password'>";
	}
	?>
	<tr><td colspan='2'><input type='submit' name='in-submit' <?php if($null){ echo "value='Confirm'"; }else{ echo'Submit'; } ?> /><?php if(isset($output)){
	echo $output;}; ?></td></tr></center><br/></form></table>
</body>
</html>