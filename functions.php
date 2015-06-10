<?php
const database = 'dasna';
const debug = true;
function user_exist($u){
	include_once 'db.php';
	mysqli_select_db($db, database);
	$result = mysqli_query($db, "SELECT name FROM users");
	while($row = mysqli_fetch_array($result)){
		if($row[0] === $u){
			return true;
		}
	}
	return false;
};
function save_carrier($carrier, $username){
		include 'db.php';
		mysqli_select_db($db, database);
		$query = "UPDATE users SET carrier = '$carrier' WHERE name = '$username'";
		if($result = mysqli_query($db, $query)){
				if(debug){echo 'Carrier saved in database!<br/>';};
				return true;
		}else{
				if(debug){echo 'Carrier not stored in database!<br/>';};
				return false;
		}
};
function row_null($column_name, $user){
	include('db.php');
	mysqli_select_db($db, database);
	$query = "SELECT $column_name FROM users WHERE name = '$user'";
	$result = mysqli_query($db, $query);
	$array = mysqli_fetch_array($result);
	$length = strlen($array[0]);
	$value = $array[0];
	if(debug){echo 'Field Value: "' . $value . '"<br/>';};
	if(debug){echo 'Length: ' . $length . '<br/>';};
	if($value === NULL){
		return true;
	}else{
		return false;
	}
};
function allgood($array){ // Returns false if not alphanumeric or is empty
	$alpha = true; // true unless something changes it
	foreach($array as $key => $value){
		if (ctype_alnum($value)) {
			if(debug){echo 'Field(' . $key . ') is alphanumeric.<br/>';};
			if(strlen($value) < 3){
				return false;
			}
		}else{
			$alpha = false;
			if(empty($value)){
				echo '<b>Field Empty!</b><br/>';
			}else{
				echo 'Fields may only contain alphanumeric characters!<br/>';
				if(debug){echo 'Field(<b>' . $key . '</b>) is not completely alphanumeric.<br/>';};
			}
		}
	}
	if($alpha === true){
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
	if(debug){echo 'Field Value: "' . $value . '"<br/>';};
	if(debug){echo 'Length: ' . $length . '<br/>';};
	return $value;
};
function read_hash($user){
	include('db.php');
	mysqli_select_db($db, database);
	$query = "SELECT phash FROM users WHERE name = '$user'";
	if($result = mysqli_query($db, $query)){
		$array = mysqli_fetch_array($result);
		if ($array[0] === NULL){
			return 'Row is null!';
		}else{
		return $array[0];
		}
	}else{
		return false;
	}
};
function read_salt($user){
	include('db.php');
	mysqli_select_db($db, database);
	$query = "SELECT salt FROM users WHERE name = '$user'";
	if($result = mysqli_query($db, $query)){
		$array = mysqli_fetch_array($result);
		return $array[0];
	}else{
		return false;
	}
};
function sendSMS($to_add, $from_add, $message, $subject){
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
function create_hash($userinput, $salt, $algo, $iter){
	if(debug){echo '<b>Iterations:' . $iter . '</b><br/>';};
	$i = 0;
	$hash = hash($algo, $salt . $userinput);
	if(debug){echo '<b>Input:</b> ' . $userinput . ' <b>Salt:</b> ' . $salt . ' <b>Hash:</b> ' . $hash . '<br/>';};
	while($i <= $iter){
		if($i % 100 == 0){ // to create variation
			$hash = str_rot13($hash);
		}else{
			$hash = strrev($hash);
		}
		$hash = hash($algo, $hash);
		// if(debug){echo '[' . $i . '](' . $algo . ')' . '->' . $hash . '<br/>';};
		$i++;
	}
	if(debug){echo '<b>Final Hash->' . $hash . '</b><br/>';};
	return $hash;
};
if(!function_exists('hash_equals')) {
  function hash_equals($str1, $str2){
    if(strlen($str1) != strlen($str2)){
      return false;
    }else{
    $string = $str1 ^ $str2;
    $return = 0;
	for($i = strlen($string) - 1;$i >= 0;$i--){
		$return |= ord($string[$i]);
	}
	return !$return;
    }
  };
}
function determine_magic_quotes($str){
	if(get_magic_quotes_gpc()){
		$ret = stripslashes($str);
	}else{
		$ret = $str;
	}
	return $ret;
};
?>