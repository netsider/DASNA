<?php
include_once('options.php');
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
	function phash_null($u){
		include('db.php');
		mysqli_select_db($db, database);
		$query = "SELECT phash FROM users WHERE name = '$u'";
		$result = mysqli_query($db, $query);
		$array = mysqli_fetch_array($result);
		$length = strlen($array[0]);
		$value = $array[0];
		echo '<font color="green">Field Value: "' . $value . '"</font><br/>';
		echo '<font color="blue">Length: ' . $length . '</font><br/>';
		if($value === NULL){
			return true;
		}else{
			return false;
		}
	};
	function temp_null($u){
		include('db.php');
		mysqli_select_db($db, database);
		$query = "SELECT temp FROM users WHERE name = '$u'";
		$result = mysqli_query($db, $query);
		$array = mysqli_fetch_array($result);
		$length = strlen($array[0]);
		$value = $array[0];
		echo '<font color="green">Field Value: "' . $value . '"</font><br/>';
		echo '<font color="blue">Length: ' . $length . '</font><br/>';
		if($value === NULL){
			return true;
		}else{
			return false;
		}
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
		$input_clean = true;
		$username = $_POST['in-user'];
		if(isset($_POST['in-pass'])){
			$password = $_POST['in-pass'];
		}
		if(isset($_POST['in-phone'])){
			$phone = $_POST['in-phone'];
			$phone_set = true;
		}
		if(isset($_POST['in-pass-new'])){
			$pass_new = $_POST['in-pass-new'];
		}
		if(user_exist($username)){
			$user_exist = true;
			if(phash_null($username) === true){
				$output = '<font color="green"><b>Type an alphanumeric password to be your password.</font></b>';
				$null = true;
			}else{
				$output = '<font color="red"><b>Username exists, but has password value!</font></b>';
				$null = false;
			}
		}else{
			$output = '<font color="red"><b>User does not exist<b></font>!';
			$user_exist = false;
		}
		if($null){ // If password for username entered is NULL
			if($user_exist && $phone_set){
				$output = 'Username exists, but confirmation code already has value.  Confirmation code NOT null.';
				if(temp_null($username) === true){
					$output = 'Confirmation code value null.  Confirmation code has been generated and sent to phone number in database.';
					if($_POST['in-submit'] === "Confirm" && isset($_POST['in-phone'])){ // executed when all critera met (Change so that in-phone is in-verify)
					echo 'Test';
					echo '<pre>';
					print_r($_POST);
					echo '</pre><br/>';
					$sp = '/r/n';
					$output = 'Password Set!';
					echo 'Attempting Mail...'; 
					echo '<br/><pre>';
					$from_add = "mailserver@dasna.net";
					$to_add = "4434972008@vzwpix.com";
					// $to_add = "4434972008@vtext.com";
					$subject = "Test Subject";
					$message = 'Test Message';
					$headers = "From: $from_add \n";
					$headers .= "Reply-To: $from_add \n";
					$headers .= "Return-Path: $from_add \n";
					$headers .= "X-Mailer: PHP \n";
					$headers .= "Content-type:text/plain;charset=UTF-8" . "\n";
					if(mail($to_add,$subject,$message,$headers)) 
					{
						$msg = "Mail sent OK";
					} 
					else 
					{
					   $msg = "Error sending email!";
					}
					echo $msg;
					}		
			}}
		}
		
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>5</title>
</head>
<body>
	<?php echo '1'; ?> 
	<center>
	<table width='25%' border='1'>
	<tr><td colspan="2"><center>Set Password</center></td></tr>
	<form action='reset.php' method='POST'>
	<tr><td>Username:</td><td>
	<?php 
	echo "<input type='text' name='in-user'";
	if($user_exist){ echo "value='$username'";}; 
	if($user_exist){ 
		echo 'disabled';
	}
	echo "/></td></tr>";
	if($user_exist){
		echo '<tr><td>Phone Number:</td><td><input type="text" name="in-phone"';
		if(isset($phone)){
		echo "value='$phone'";
			echo ' disabled';
		} 
		echo '/></td></tr>';
	}
	if($user_exist && $phone_set){
		echo "<tr><td><b>Confirmation Code</b>:</td><td><input type='text' name='in-conf'";
		// if(isset($new_pass)){
			// echo "value='$pass_new'";
			// echo ' disabled';
		// }
		echo "/></td></tr>";
	}
	if($user_exist && $phone_set){
	echo "<tr><td><b>New Password</b>:</td><td><input type='password' name='in-pass'";
	if(isset($_POST['in-pass-new'])){
		echo "value='$password'";
		echo ' disabled';
	}
	echo "/></td></tr>";
	}
	if($user_exist && $phone_set){
		echo "<tr><td><b>Re-type Password</b>:</td><td><input type='password' name='in-pass-new'";
		if(isset($new_pass)){
			echo "value='$pass_new'";
			echo ' disabled';
		}
		echo "/></td></tr>";
	}
	echo "<tr><td colspan='2'><center><input type='submit' name='in-submit'"; // Submit button
	if($null){ 
		echo "value='ConfirmUserandPhone'";
	}else{ 
		echo "value='Submit'"; 
	} 
	echo '/></center></td></tr>';
	echo '<tr><td colspan="2"><u><center>Notes:</center></u>';
	if(isset($output)){ echo $output;};
	echo '</td></tr>';
	// Retain data between submits
	if(isset($username) && $user_exist){
		echo "<input type='hidden' name='in-user' value='$username' />";
	}
	if(isset($password)){
		echo "<input type='hidden' name='in-pass' value='$password' />";
	}
	if(isset($phone)){
		echo "<input type='hidden' name='in-phone' value='$phone' />";
	} 
	?>
	</center><br/></form></table>
</body>
</html>