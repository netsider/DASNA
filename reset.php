<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once('options.php');
$br = '<br/>';
$fcg = '<font color="green">';
$fcr = '<font color="red">';
$efc = '</font>';
$efcbr = '</font><br/>';
$output = '<br/>';
$confirm = false; // Whether or not confirmation code has been entered by user and present in POST
$phone_set = false; // Whether phone number present in POST data
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
	function pick_carrier($string){
			switch ($string){
				case "verizon":
					$carrier = '@vzwpix.com';
					break;
				case "att":
					$carrier = '@txt.att.net';
					break;
				default:
					$carrier = false;
					echo $fcr . 'Carrier not set!' . $efcbr;
				return $carrier;
			}
	}
	function save_carrier($carrier, $username){
			include('db.php');
			mysqli_select_db($db, database);
			$query = "UPDATE users SET carrier = '$carrier' WHERE name = '$username'";
			if($result = mysqli_query($db, $query)){
					echo 'Carrier saved in database!<br/>';
					return true;
			}else{
					echo 'Carrier not stored in database!<br/>';
					return false;
			}
	}
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
		echo '<font color="red">Field Value: "' . $value . '"</font><br/>';
		echo '<font color="red">Length: ' . $length . '</font><br/>';
		return $value;
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
			return false;
		}
	};
	function sendSMS($to_add, $from_add, $message){
		$subject = "Confirmation";
		$headers = "From: $from_add \r\n";
		$headers .= "Reply-To: $from_add \r\n";
		$headers .= "Return-Path: $from_add\r\n";
		$headers .= "X-Mailer: PHP \r\n";
		$headers .= "Content-type:text/plain;charset=UTF-8 \r\n";
		if(mail($to_add,$subject,$message,$headers)){
			return true;
		}else{
			return false;
		}
	};
	function save_confirm_code($code, $user){
		include('db.php');
		mysqli_select_db($db, database);
		$query = "UPDATE users SET temp = '$code' WHERE name = '$user'";
		if($result = mysqli_query($db, $query)){
			return true;
		}else{
			return false;
		}
	};
	function validate($p1, $p2){
		if($p1 === $p2){
			return true;
		}else{
			return false;
		}
	};
	function save_hash($user, $hash){
		include('db.php');
		mysqli_select_db($db, database);
		$query = "UPDATE users SET phash = '$hash' WHERE name = '$user'";
		if(mysqli_query($db, $query)){
			return true;
		}else{
			return false;
		}
	};
	function save_salt($user, $salt){
		include('db.php');
		mysqli_select_db($db, database);
		$query = "UPDATE users SET salt = '$salt' WHERE name = '$user'";
		if(mysqli_query($db, $query)){
			return true;
		}else{
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
		if(isset($_POST['in-conf'])){
			$confirm_code = $_POST['in-conf'];
			$confirm_set = true;
		}
		if(isset($_POST['in-carrier'])){
			$carrier = $_POST['in-carrier'];
			$carrier_set = true;
		}	
		if(user_exist($username)){
			$user_exist = true;
			if(row_null('phash', $username) === true){
				$output .= 'Username has no password value.  Proceed to set.' . $br;
				$null = true;
			}else{
				$output .= $fcr . 'Username exists, but already has value!' . $efcbr;
				$null = false;
			}
		}else{
			$output .= $fcr . '<b>User does not exist<b>!' . $efcbr;
			$user_exist = false;
		}
		if($null){
			if($user_exist && $phone_set){
				$phone_fromDB = row_value('phone', $username);
				if($phone_fromDB === $phone){
					$output .= $fcg . 'Phone number exists/matches!' . $efcbr;
					$phone_match = true;
					if(!$confirm_set && $carrier_set){
						$mobile_carrier = pick_carrier($carrier);
						if(save_carrier($mobile_carrier, $username)){
							$to_add = $phone_fromDB . $mobile_carrier;
							$c = mt_rand(1000000, 9999999);
							// $output .= 'Attempting to send SMS...' . $br;
							// if(sendSMS($to_add, 'mailserver@dasna.net', $c)){
								// $output .= $fcg . "Confirmation code sent to $to_add. Check your mobile device text messages for the code." . $efcbr;
								// if(save_confirm_code($c, $username)){
									// $output .= $fcg . "Confirmation code recorded. Please enter it continue" . $efcbr;
								// }
							// }else{
								// $output .= $fcr . 'Error sending SMS!' . $efcbr;
							// }
						}
					}
					if(row_value('temp', $username) === $confirm_code){
						$output .= $fcg . 'Confirmation code confirmed by database!  You can now set your new password.' . $efcbr;
						$confirm = true;
						$parray = array(); // create an array to pass into the allgood function
						if(isset($password) && isset($pass_new)){
							$bothset = true;
							if(validate($pass_new, $password)){
								$output .= $fcg . 'Passwords OK!' . $efc;
								// $iterations = 250000;
								$salt = mcrypt_create_iv(20, MCRYPT_RAND);  //Can also or openssl_random_psuedo_bytes()
								$i = 1;
								$string = $password;
								// $salt = 'russell';
								$hash = hash('whirlpool', $string . $salt);
								while($i < 10000){
									$hash = hash('whirlpool', $hash);
									// echo 'String: ' . $string . ' Salt: ' . $salt . $br;
									echo $i . '.-->Hash: ' . $hash . $br;
									// $string = $hash;
									$i++;
								}
								// $hash = hash_pbkdf2("sha256", $password, $salt, $iterations, 0);
								$output .= 'Attempting to save to database...' . $efcbr;
								// $options = '$2a$07$' . $salt . '$';
								// $hash = crypt($password, $options);
								$output .= '<b>Final Hash Generated: ' . $hash . '</b><br/> Salt: ' . $salt . ' Salted-Hash: ' . $salted_pass . $efcbr;
								// if(save_hash($username, $hash)){
									// $output .= $fcg . 'Password hash saved to database!' . $efcbr;
								// }else{
									// $output .= $fcr . 'Password hash NOT saved to database!' . $efcbr;
								// }
								// if(save_salt($username, $salt)){
									// $output .= $fcg . 'Password salt saved to database!' . $efcbr;
								// }else{
									// $output .= $fcr . 'Password salt NOT saved to database!' . $efcbr;
								// }
							}
						}
					}else{
						$confirm = false;
					}
				}				
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="ISO-8859-1">
	<title>38</title>
</head>
<body>
	<center>
	<table width='25%' border='1'>
	<tr><td colspan="2"><center>Set Password</center></td></tr>
	<form action='reset.php' method='POST'>
	<tr><td>Username:</td><td>
	<?php 
	echo "<input type='text' name='in-user'";
	if(isset($username)){ echo "value='$username'";}; 
	if($user_exist){ 
		echo 'disabled';
	}
	echo "/></td></tr>";
	if($user_exist){
		echo '<tr><td>Phone Number:</td><td><input type="text" name="in-phone"';
		if($phone_set){
			echo "value='$phone'";
		}
		if($phone_match){ echo ' disabled';};
		echo '/></td></tr>';
	}
	if($user_exist){
		echo '<tr><td>Mobile Carrier:</td><td>';
		echo '<select name="in-carrier"';
		if(isset($carrier)){ echo ' disabled';};
		echo '>';
			echo '<option value="verizon">Verizon</option>';
			echo '<option value="att">ATT</option>';
		echo '</select>';
		echo '</td></tr>';
	}
	if($phone_match){
		echo "<tr><td><b>Confirmation Code</b>:</td><td><input type='text' name='in-conf'";
		if(isset($confirm_code)){
			echo "value='$confirm_code'";
			if($confirm){ echo ' disabled';};
		}
		echo "/></td></tr>";
	}
	if($confirm){
		echo "<tr><td><b>New Password</b>:</td><td><input type='password' name='in-pass'";
		if(isset($_POST['in-pass'])){
			echo "value='$password'";
			echo ' disabled';
		}
		echo "/></td></tr>";
	}
	if($confirm){
		echo "<tr><td><b>Re-type Password</b>:</td><td><input type='password' name='in-pass-new'";
		if(isset($_POST['in-pass-new'])){
			echo "value='$pass_new'";
			echo ' disabled';
		}
		echo "/></td></tr>";
	}
	echo "<tr><td colspan='2'><center><input type='submit' name='in-submit'"; // Submit button
	if($null){ 
		echo "value='Finish'";
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
	if($bothset){
		echo "<input type='hidden' name='in-pass' value='$password' />";
	}
	if($bothset){
		echo "<input type='hidden' name='in-pass-new' value='$pass_new' />";
	}
	if(isset($phone)){
		echo "<input type='hidden' name='in-phone' value='$phone' />";
	}
	if(isset($carrier)){
		echo "<input type='hidden' name='in-carrier' value='$carrier' />";
	}
	if($confirm){
		$confirm_code = $_POST['in-conf'];
		echo "<input type='hidden' name='in-conf' value='$confirm_code' />";
	}
	?>
	</center><br/></form></table>
</body>
</html>