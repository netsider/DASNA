<?php
require_once 'functions.php';
require_once 'vars.php';
const debug = true;
const database = 'dasna';
if($_POST['in-submit']){
	if(allgood($_POST)){
		$input_clean = true;
		if(isset($_POST['in-user'])){
			$username = $_POST['in-user'];
		}
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
				$output .= $fcg . 'Password for user has no value.  Password will be set...' . $efcbr;
				$null = true;
			}else{
				$output .= $fcr . 'User already has password value stored in database.  Password will be reset...' . $efcbr;
				$null = false;
			}
			if(row_null('phone', $username) && !$carrier_set){
				$output .= $fcr . 'User phone # not currently set.  Please enter your phone number to continue.' . $efcbr;
				$phone_null = true;
			}elseif(!row_null('phone', $username)){
				$output .= $fcg . 'User phone # exists in database.' . $br;
				$phone_null = false;
			}
		}else{
			$output .= $fcr . 'User does not exist!' . $efcbr;
			$user_exist = false;
		}
		if($null OR !$null){ // Remove !$null to only set passwords that are blank, or keep both to reset current passwords
			if($user_exist && $phone_set){
				if(row_null('phone', $username) && isset($phone)){
					if(debug){$output .= 'Phone does not exist in database, but is set by user...<br/>';};
					include('db.php');
					mysqli_select_db($db, database);
					$query = "UPDATE users SET phone = '$phone' WHERE name = '$username'";
					if($result = mysqli_query($db, $query)){
						$output .= $fcg . '<b>Phone number saved!</b>' . $efcbr;
						$phone_saved = true;
					}else{
						$output .= $fcr . '<b>Phone number NOT saved!</b>' . $efcbr;
					}
				}
				if(check_equal($phone_fromDB = row_value('phone', $username), $phone)){
					$output .= $fcg . 'Phone number exists/matches!' . $efcbr;
					$phone_match = true;
					if(!$confirm_set && $carrier_set){
						$mobile_carrier = pick_carrier($carrier);
						if(save_carrier($mobile_carrier, $username)){
							$to_add = $phone_fromDB . $mobile_carrier;
							$conf_length = 7;
							for ($x = 0; $x < $conf_length; $x++) {
								$int = mt_rand(0, 9);
								$c .= $int;
							}					
							$output .= 'Attempting to send SMS...' . $br;
							if(sendSMS($to_add, 'mailserver@dasna.net', $c, 'Confirmation')){ // change back to "" if not working later
								$output .= $fcg . "Confirmation code sent to $to_add. Check your mobile device text messages for the code." . $efcbr;
								if(save_confirm_code($c, $username)){
									$output .= $fcg . "Confirmation code recorded. Please enter it to continue" . $efcbr;
								}
							}else{
								$output .= $fcr . 'Error sending SMS!' . $efcbr;
							}
						}
					}
					if(row_value('temp', $username) === $confirm_code){
						$output .= $fcg . 'Confirmation code confirmed by database!  You can now set your new password.' . $efcbr;
						$confirm = true;
						$parray = array(); // an array for the allgood function
						if(isset($password) && isset($pass_new)){
							$bothset = true;
							if(check_equal($pass_new, $password)){ // check if both the same
								$iterations = 100000;
								$output .= $fcg . 'Password OK!' . $efcbr;
								$salt = hash('ripemd320', mcrypt_create_iv(20, MCRYPT_RAND));						
								$hash = create_hash(create_hash($password, $salt, 'ripemd320', $iterations), $salt, 'whirlpool', $iterations);
								if(debug){$output .= '<b>Final Hash Generated: ' . $hash . '</b><br/> Salt: ' . $salt . $efcbr;};
								$output .= 'Attempting to save to database...' . $efcbr;			
								if(save_hash($username, $hash)){ // save hash
									$output .= $fcg . 'Password saved to database.' . $efcbr;
								}else{
									$output .= $fcr . 'Password NOT saved to database.' . $efcbr;
								}
								if(save_salt($username, $salt)){ // save salt
									$output .= $fcg . 'Salt saved to database!' . $efcbr;
								}else{
									$output .= $fcr . 'Salt NOT saved to database!' . $efcbr;
								}
								if(validate($username, $password)){ // Check it
									$output .= 'Password Successfully set!  Please close this window (to login  later), or <a href="editor.php">proceed</a>.';
								}else{
									$output .= 'Fail!';
								}
							}
						}
					}else{
						$out .= $fcr . 'Confirm code does not match that in database.' . $efcbr;
						$confirm = false;
					}
				}else{
					$output .= $fcr . 'Phone number entered does not match value in DB!' . $efcbr;
				}				
			}
		}else{
			$output .= $fcr . 'Password does not meet requirements to be reset!' . $efcbr;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html"; charset="iso-8859-1" />
	<title>User Reset Form</title>
</head>
<body>
	<center>
	<table width='25%' border='1'>
	<tr><td colspan="2"><center>Set Password</center></td></tr>
	<form action='reset.php' method='POST' name="Form" id="Form">
	<tr><td>Username:</td><td>
	<?php 
	echo "<input type='text' name='in-user'";
	if(isset($username)){ echo "value='$username'";}; 
	if($user_exist){ 
		echo 'disabled';
	}
	echo 'onkeyup="validateForm()"/></td></tr>';
	if($user_exist){
		echo '<tr><td>Phone Number:</td><td><input type="text" name="in-phone"';
		if($phone_set){
			echo "value='$phone'";
		}
		if($phone_match){ echo ' disabled';};
		echo 'onkeyup="validateForm()"/></td></tr>';
	}
	if($user_exist){
		echo '<tr><td>Mobile Carrier:</td><td>';
		echo '<select name="in-carrier" onchange="validateForm()"';
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
	echo "<tr><td colspan='2'><center><input type='submit' name='in-submit' id='in-submit'"; // Submit button
	if($null){ 
		echo "value='Finish'";
	}else{ 
		echo "value='Submit'"; 
	} 
	echo '/></center></td></tr>';
	echo '<tr><td colspan="2"><u><center>Notes:</center></u>';
	if(isset($output)){ echo $output;};
	echo '</td></tr>';
	if(isset($username) && $user_exist){ // To retain data between submits (before I realized how to do it the rigth way)
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
<script>
document.getElementById("in-submit").disabled = true;
function validateForm(){
		var toCheck = new Array();
		var trueArray = new Array();
		// toCheck["user"] = document.forms["Form"]["in-user"].value;
        var elem = document.getElementById('Form').elements;
        for(var i = 0; i < elem.length;i++){
			console.log("Name: " + elem[i].name + "| Value: " + elem[i].value);
			if(elem[i].name === "in-user"){
				toCheck["user"] = elem[i].value;
				// console.log("User: " + toCheck["user"]);
			}
			if(elem[i].name === "in-phone"){
				toCheck["phone"] = elem[i].value;
				// console.log("User: " + toCheck["phone"]);
			}
			if(elem[i].name === "in-carrier"){
				toCheck["carrier"] = elem[i].value;
				// console.log("Carrier: " + toCheck["phone"]);
			}
			if(check_alphanum(elem[i].value)){
				trueArray.push(1);
			}else{
				trueArray.push(0);
			}
		}
		if(toCheck["user"] != undefined){
			var user = toCheck["user"];
			if(user.length > 0){
				trueArray.push(1);
			}else{
				trueArray.push(0);
			}
		}
		if(toCheck["phone"] != undefined){
			var phone = toCheck["phone"];
			if(phone.length >= 10){
				trueArray.push(1);
			}else{
				trueArray.push(0);
			}
			if(check_alphanum(phone.value)){
				trueArray.push(1);
			}else{
				trueArray.push(0);
			}
		}
		if(toCheck["carrier"] != undefined){
			var carrier = toCheck["carrier"];
			if(carrier.length > 0){
				trueArray.push(1);
			}
		}
		var ret = true;  // true unless below changes it
		for(var i = 0;i < trueArray.length;i++){
			console.log(trueArray[i]);
			if(trueArray[i] != 1){
				console.log("False");
				ret = false;
			}else{
				console.log("True");
			}
		}
		if(ret === true){
			toggleSubmit(true);
		}else{
			toggleSubmit(false);
		}
}
function toggleSubmit(bool){
	if(bool === true){
		document.getElementById("in-submit").disabled = false;
	}else{
		document.getElementById("in-submit").disabled = true;
	}
};
function check_alphanum(code){
    if(/[^a-zA-Z0-9]/.test(code)){
       return false;
    }
    return true;     
}
</script>
</body>
</html>