<?php
require_once 'functions.php';
require_once 'vars.php';
const Fdebug = true;
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
				$output .= '<span class="success">User password value in database NULL.</span><br/>';
				$null = true;
			}else{
				$output .= 'User already has password stored in database (password will be reset).<br/>';
				$null = false;
			}
			if(row_null('phone', $username) && !$carrier_set){
				$output .= '<span class="fail">User contact information not found in DB (Please enter contact address).</span></br>';
				$phone_null = true;
			}elseif(!row_null('phone', $username)){
				if(Fdebug){$output .= '<span class="success">User contact information exists in database.</span><br/>';};
				$phone_null = false;
			}
		}else{
			$output .= $fcr . 'User does not exist!' . $efcbr;
			$user_exist = false;
		}
		if($user_exist && $phone_set){
			if(read_changeable($username) === "1"){
				$output .= 'User dynamic!<br/>';
				$changeable = true;
			}else{
				$output .= 'User static!<br/>';
				$changeable = false;
		}
		if(isset($phone)){
				if($changeable === true || $phone_null === true){
						if(row_null('phone', $username)){
							if(Fdebug){$output .= "User contact information NULL.<br/>";};
						}else{
							if(Fdebug){$output .= "User contact information exists.<br/>";};
						}
						if($changeable === true){
							if(Fdebug){$output .= "User contact information changeable!<br/>";};
						}else{
							if(Fdebug){$output .= "User contact information NOT changeable!<br/>";};
						}
						if(save_phone($phone, $username)){
							$output .= '<span class="success">Phone number saved!</span><br/>';
							$phone_saved = true;
						}else{
							$output .= '<span class="fail">Phone number NOT saved!</span><br/>';
						}
					}else{
						if(Fdebug){$output .= $fcr . 'Phone exists in database, or "changeable" set to FALSE... (number cannot be changed, but can/will be verified)' . $efcbr;};
				}
			}
				if(check_equal($phone_fromDB = row_value('phone', $username), $phone)){
					$output .= '<span class="success">Phone number exists/matches!</span><br/>';
					$phone_match = true;
					if(!$confirm_set && $carrier_set){
						$mobile_carrier = pick_carrier($carrier);
						if($mobile_carrier === 'email'){
							$to_add = $phone_fromDB;
						}else{
							$to_add = $phone_fromDB . $mobile_carrier;
						}
						if(save_carrier($mobile_carrier, $username)){
							$conf_length = 7;
							for ($x = 0; $x < $conf_length; $x++) {
								$int = mt_rand(0, 9);
								$c .= $int;
							}
							$output .= 'Attempting to send confirmation code...<br/>';
							if(sendSMS($to_add, 'mailserver@dasna.net', $c, 'Confirmation Code')){ // change back to "" if not working later
								$output .= "<span class='success'>Confirmation code sent to $to_add. Check your mobile device text messages for the code.</span><br/>";
								if(save_confirm_code($c, $username)){
									$output .= "<span class='success'>Confirmation code recorded. Please enter it to continue</span><br/>";
								}
							}else{
								$output .= '<span class="fail">Error sending confirmation code!</span><br/>';
							}
						}
					}
					if(row_value('temp', $username) === $confirm_code){
						$output .= $fcg . 'Confirmation code confirmed by database!  You can now set your new password.' . $efcbr;
						$confirm = true;
						$parray = array(); // an array, since the allgood function only takes arrays
						if(isset($password) && isset($pass_new)){
							$bothset = true;
							if(check_equal($pass_new, $password)){
								$iterations = 100000;
								$output .= $fcg . 'Password OK!' . $efcbr;
								$salt = hash('ripemd320', mcrypt_create_iv(20, MCRYPT_RAND));						
								$hash = create_hash(create_hash($password, $salt, 'ripemd320', $iterations), $salt, 'whirlpool', $iterations);
								if(Fdebug){$output .= '<b>Final Hash Generated: ' . $hash . '</b><br/> Salt: ' . $salt . $br;};
								$output .= 'Attempting to save to database...' . $efcbr;			
								if(save_hash($username, $hash)){
									$output .= '<span class="success">Password saved to database.</span><br/>';
								}else{
									$output .= '<span class="fail">Password NOT saved to database.</span><br/>';
								}
								if(save_salt($username, $salt)){
									$output .= '<span class="success">Salt saved to database!</span><br/>';
								}else{
									$output .= '<span class="fail">Salt NOT saved to database!</span><br/>';
								}
								if(validate($username, $password)){
									$output .= 'Password Successfully set!  Please close this window (to login  later), or <a href="editor.php">proceed</a>.';
								}else{
									$output .= 'Fail!';
								}
							}
						}
					}else{
						$out .= $fcr . '<span class="fail">Confirm code does not match that in database.</span><br/>';
						$confirm = false;
					}
				}else{
					$output .= '<span class="fail">Phone number entered does not match value in DB</span><br/>';
				}				
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
<style>
.success{
	color: green;
}
.fail{
	color: red;
}
</style>
<body>
	<center>
	<table width='25%' border='1'>
	<tr><td colspan="2"><center>Set Password</center></td></tr>
	<form action='reset.php' method='POST' name="Form" id="Form">
	<tr><td>User:</td><td>
	<?php 
	echo "<input type='text' name='in-user'";
	if(isset($username)){ echo "value='$username'";}; 
	if($user_exist){ 
		echo ' disabled';
	}
	echo ' onkeyup="validateForm()" onblur="validateForm()"></input>&nbsp<div id="yesbox1" style="display: none;"><img src="check-mark.png" style="height: 12px;width: 12px;"/></div></td></tr>';
	if($user_exist){
		echo '<tr><td><div id="whattoselect">Phone/Email:</div></td><td><input type="text" name="in-phone"';
		if($phone_set){
			echo "value='$phone'";
		}
		if($phone_match){ echo ' disabled';};
		echo ' onkeyup="validateForm()" onblur="validateForm()"></input>&nbsp<div id="yesbox2" style="display: none;"><img src="check-mark.png" style="height: 12px;width: 12px;"/></td></tr>';
	}
	if($user_exist && !$carrier_set){
		echo '<tr><td>Delivery Method:</td><td>';
		echo '<select name="in-carrier" onchange="validateForm()"';
		if(isset($carrier)){ echo ' disabled';};
		echo '>';
			echo '<option value="Choose">Choose</option>';
			echo '<option value="email">Use e-mail</option>';
			echo '<option value="verizon">Verizon</option>';
			echo '<option value="att">AT&T</option>';
		echo '</select>&nbsp<div id="yesbox3" style="display: none;"><img src="check-mark.png" style="height: 12px;width: 12px;"/>';
		echo '</td></tr>';
	}
	if($phone_match){
		echo "<tr><td><b>Confirmation Code</b>:</td><td><input type='text' name='in-conf'";
		if(isset($confirm_code)){
			echo "value='$confirm_code'";
			if($confirm){ echo ' disabled';};
		}
		echo ' onkeyup="validateForm()"/>&nbsp<div id="yesbox4" style="display: none;"><img src="check-mark.png" style="height: 12px;width: 12px;"/></td></tr>';
	}
	if($confirm){
		echo "<tr><td><b>New Password</b>:</td><td><input type='password' name='in-pass'";
		if(isset($_POST['in-pass'])){
			echo "value='$password'";
			echo ' disabled';
		}
		echo ' onkeyup="validateForm()" />&nbsp<div id="yesbox5" style="display: none;"><img src="check-mark.png" style="height: 12px;width: 12px;"/></td></tr>';
	}
	if($confirm){
		echo "<tr><td><b>Re-type Password</b>:</td><td><input type='password' name='in-pass-new'";
		if(isset($_POST['in-pass-new'])){
			echo "value='$pass_new'";
			echo ' disabled';
		}
		echo ' onkeyup="validateForm()" />&nbsp<div id="yesbox6" style="display: none;"><img src="check-mark.png" style="height: 12px;width: 12px;"/></td></tr>';
	}
	echo "<tr><td colspan='2'><center><input type='submit' name='in-submit' id='in-submit'";
	if($null){ 
		echo "value='Finish'";
	}else{ 
		echo "value='Submit'"; 
	} 
	echo '/></center></td></tr>';
	echo '<tr><td colspan="2"><u><center>Notes:</center></u>';
	if(isset($output)){ echo $output;};
	echo '</td></tr>';
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
<script>
validateForm();
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
			if(elem[i].name === "in-pass"){
				toCheck["pass"] = elem[i].value;
				// console.log("Carrier: " + toCheck["phone"]);
			}
			if(elem[i].name === "in-pass-new"){
				toCheck["pass-new"] = elem[i].value;
				// console.log("Carrier: " + toCheck["phone"]);
			}
			if(check_alphanum(elem[i].value.replace('@', 'AT').replace('.', 'DOT'))){
				trueArray.push(1);
			}else{
				trueArray.push(0);
			}
		}
		if(toCheck["user"] != undefined){
			var user = toCheck["user"];
			if(user.length > 0){
				trueArray.push(1);
				document.getElementById("yesbox1").style.display = "inline";
			}else{
				trueArray.push(0);
				document.getElementById("yesbox1").style.display = "none";
			}
		}
		if(toCheck["phone"] != undefined){
			var phone = toCheck["phone"];
			if(phone.length > 0){
				trueArray.push(1);
			}else{
				trueArray.push(0);
			}
		}
		if(toCheck["carrier"] != undefined){
			var carrier = toCheck["carrier"];
			if(carrier.length > 0){
				trueArray.push(1);
				document.getElementById("yesbox2").style.display = "inline";
			}else{
				trueArray.push(0);
				document.getElementById("yesbox2").style.display = "none";
			}
			if(carrier === "Choose"){
				trueArray.push(0);
				document.getElementById("yesbox2").style.display = "none";
			}else{
				trueArray.push(1);
				if (document.getElementById("yesbox3") != undefined){
					document.getElementById("yesbox3").style.display = "inline";
				}
			}
			if(carrier === "email"){
				document.getElementById("whattoselect").innerHTML = "Email:";
				if(phone.match('@')){
					trueArray.push(1);
					document.getElementById("yesbox2").style.display = "inline";
				}else{
					trueArray.push(0);
					document.getElementById("yesbox2").style.display = "none";
				}
				if(phone.match('.com')){
					trueArray.push(1);
					document.getElementById("yesbox2").style.display = "inline";
				}else{
					trueArray.push(0);
					document.getElementById("yesbox2").style.display = "none";
				}
			}
			if(carrier === "verizon" || carrier === "att"){
				document.getElementById("whattoselect").innerHTML = "Phone:";
				var reg = new RegExp('^\\d+$');
				console.log("Phone is: " + reg.test(phone));
				if(!reg.test(phone)){
					trueArray.push(0);
					document.getElementById("yesbox2").style.display = "none";
				}else{
					trueArray.push(1);
				}
				if(phone.length === 10){
					trueArray.push(1);
					document.getElementById("yesbox2").style.display = "inline";
				}else{
					trueArray.push(0);
					document.getElementById("yesbox2").style.display = "none";
				}
			}
		}
		var passLength = 6;
		if(toCheck["pass"] != undefined){
			var pass = toCheck["pass"];
			if(pass.length > passLength){
				trueArray.push(1);
				document.getElementById("yesbox5").style.display = "inline";
			}else{
				trueArray.push(0);
				document.getElementById("yesbox5").style.display = "none";
			}
		}
		if(toCheck["pass-new"] != undefined){
			var pass_new = toCheck["pass-new"];
			if(pass_new.length > passLength){
				trueArray.push(1);
			}else{
				trueArray.push(0);
				document.getElementById("yesbox6").style.display = "none";
			}
		
		}
		if(pass_new === pass){
			trueArray.push(1);
			document.getElementById("yesbox6").style.display = "inline";
		}else{
			trueArray.push(0);
			document.getElementById("yesbox6").style.display = "none";
		}
		if(pass_new.length < 1){
			document.getElementById("yesbox6").style.display = "none";
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