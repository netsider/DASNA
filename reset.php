<?php
error_reporting(E_ALL ^ E_NOTICE);ini_set('display_errors',1);
include_once('options.php');
$br = '<br/>';
$output = '';
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
		return false;
	};
	function row_null($column_name, $user){
		include('db.php');
		mysqli_select_db($db, database);
		$query = "SELECT $column_name FROM users WHERE name = '$user'";
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
	function row_value($column_name, $user){
		include('db.php');
		mysqli_select_db($db, database);
		$query = "SELECT $column_name FROM users WHERE name = '$user'";
		$result = mysqli_query($db, $query);
		$array = mysqli_fetch_array($result);
		$length = strlen($array[0]);
		$value = $array[0];
		echo '<font color="green">Field Value: "' . $value . '"</font><br/>';
		echo '<font color="blue">Length: ' . $length . '</font><br/>';
		// return $value;
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
			if(row_null('phash', $username) === true){
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
				$output = 'Username exists, and phone set';
				if(row_value("phone", $username) == $phone){
					echo '<b>Match!</b>';
					$code_match = true;
				}
				echo (row_value("phone", $username));
				if(row_null('temp', $username) === true){
					$output = 'Attempting mail...';
					echo 'Attempting Mail...';
					$from_add = "mailserver@dasna.net";
					$to_add = "4434972008@vzwpix.com";
					// $to_add = "4434972008@vtext.com";
					$sp = "/r/n";
					$subject = "Test Subject";
					$message = 'Test Message';
					$headers = "From: $from_add /n";
					$headers .= "Reply-To: $from_add /n";
					$headers .= "Return-Path: $from_add /n";
					$headers .= "X-Mailer: PHP /n";
					$headers .= "Content-type:text/plain;charset=UTF-8 /n";
					// if(mail($to_add,$subject,$message,$headers)){
						// echo "Mail sent OK!";
						// $output = 'Confirmation code value null.  Confirmation code generated & sent to phone number on record.';
					// }else{
						// echo "Error sending email!";
					// }
				}		
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>6</title>
</head>
<body>
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
	if($user_exist && $phone_set && $null){
		echo "<tr><td><b>Confirmation Code</b>:</td><td><input type='text' name='in-conf'";
		// if(isset($new_pass)){
			// echo "value='$pass_new'";
			// echo ' disabled';
		// }
		echo "/></td></tr>";
	}
	if($user_exist && $phone_set && $null){
	echo "<tr><td><b>New Password</b>:</td><td><input type='password' name='in-pass'";
	if(isset($_POST['in-pass-new'])){
		echo "value='$password'";
		echo ' disabled';
	}
	echo "/></td></tr>";
	}
	if($user_exist && $phone_set && $null){
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