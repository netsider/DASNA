<?php
const database = 'dasna';
const debug = false;
$br = '<br/>';
$fcg = '<font color="green">';
$fcr = '<font color="red">';
$efc = '</font>';
$efcbr = '</font><br/>';
function select_from($col, $table, $where){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "SELECT $col FROM $table WHERE $where";
	$result = mysqli_query($db, $query);
	$array = mysqli_fetch_array($result);
	return $array[0];
};
function save_phone($phone, $user){
	include('db.php');
	mysqli_select_db($db, database);
	$query = "UPDATE users SET phone = '$phone' WHERE name = '$username'";
	if($result = mysqli_query($db, $query)){
		return true;
	}else{
		return false;
	}
}
function save_content($section, $data){
	include 'db.php';
	mysqli_select_db($db, 'dasna');
	$query = "UPDATE content SET data = '$data' WHERE section = '$section'";
	if(mysqli_query($db, $query)){
		return true;
	}else{
		return false;
	}
};
function insert_into($table, $values){
	include 'db.php';
	mysqli_select_db($db, 'dasna');
	$query = "INSERT INTO $table (data) VALUES ('$values')";
	if(mysqli_query($db, $query)){
		return true;
	}else{
		return false;
	}
};
function save_salt($user, $salt){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "UPDATE users SET salt = '$salt' WHERE name = '$user'";
	if(mysqli_query($db, $query)){
		return true;
	}else{
		return false;
	}
};
function check_equal($str1, $str2){
	if(hash_equals($str1, $str2)){
		return true;
	}else{
		return false;
	}
};
function save_confirm_code($code, $user){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "UPDATE users SET temp = '$code' WHERE name = '$user'";
	if($result = mysqli_query($db, $query)){
		return true;
	}else{
		return false;
	}
};
function save_hash($user, $hash){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "UPDATE users SET phash = '$hash' WHERE name = '$user'";
	if(mysqli_query($db, $query)){
		return true;
	}else{
		return false;
	}
};
function pick_carrier($string){
	switch ($string){
	case "verizon":
		$carrier = '@vzwpix.com';
		break;
	case "att":
		$carrier = '@mms.att.net';
		break;
	case "email":
		$carrier = 'email';
		break;
	default:
		$carrier = '@vtext.com';
	}
	return $carrier;
};
function get_pageName($section){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "SELECT pagename FROM content WHERE section = '$section'";
	$result = mysqli_query($db, $query);
	$array = mysqli_fetch_array($result);
	$value = $array[0];
	return $value;
};
function read_page($user){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "SELECT db FROM users WHERE name = '$user'";
	if($result = mysqli_query($db, $query)){
		$array = mysqli_fetch_array($result);
		return $array[0];
	}
};
function read_debug($user){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "SELECT debug FROM users WHERE name = '$user'";
	if($result = mysqli_query($db, $query)){
		$array = mysqli_fetch_array($result);
		return $array[0];
	}
};
function read_changeable($user){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "SELECT changeable FROM users WHERE name = '$user'";
	if($result = mysqli_query($db, $query)){
		$array = mysqli_fetch_array($result);
		return $array[0];
	}
};
function read_column_array($table){
	include 'db.php';
	mysqli_select_db($db, database);
	// $query = "SELECT section FROM '$table'";
	$query = "SELECT section FROM `$table`";
	$result = mysqli_query($db, $query);
	$column_array = array();
	while($array = mysqli_fetch_array($result)){
	// if(debug){$output .= $array[0] . $br;};
	$column_array[] = $array[0];
	}
	return $column_array;
};
function read_content($section){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "SELECT data FROM content WHERE section = '$section'";
	$result = mysqli_query($db, $query);
	$array = mysqli_fetch_array($result);
	$length = strlen($array[0]);
	$value = $array[0];
	// if(debug){$output .= 'Field Value: "' . $value . '"<br/>';};
	// if(debug){$output .= 'Length: ' . $length . '<br/>';};
	return $value;
};
function user_exist($u){
	include 'db.php';
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
		if(mysqli_query($db, $query)){
				if(debug){$output .= 'Carrier saved in database!<br/>';};
				return true;
		}else{
				if(debug){$output .= 'Carrier not stored in database!<br/>';};
				return false;
		}
};
function save_to_DB($user, $column, $value){
		include 'db.php';
		mysqli_select_db($db, database);
		$query = "UPDATE users SET $column = '$value' WHERE name = '$user'";
		if(mysqli_query($db, $query)){
				if(debug){$output .= 'Field(<b>' . $column . '</b>) saved in database. Value:<b>' . $value . '</b><br/>';};
				return true;
		}else{
				if(debug){$output .= 'Field not saved successfully!<br/>';};
				return false;
		}
};
function update_DB($table, $column, $value, $where){
		include 'db.php';
		mysqli_select_db($db, database);
		$query = "UPDATE $table SET $column = '$value' WHERE $where";
		if(mysqli_query($db, $query)){
			if(debug){$output .= 'Column(<b>' . $column . '</b>) saved in database. Value:<b>' . $value . '</b><br/>';};
			return true;
		}else{
			if(debug){$output .= 'Column not saved successfully!<br/>';};
			return false;
		}
};
function row_null($column_name, $user){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "SELECT $column_name FROM users WHERE name = '$user'";
	$result = mysqli_query($db, $query);
	$array = mysqli_fetch_array($result);
	$length = strlen($array[0]);
	$value = $array[0];
	// if(debug){$output .= 'Field Value: "' . $value . '"<br/>';};
	// if(debug){$output .= 'Length: ' . $length . '<br/>';};
	if($value === NULL){
		return true;
	}else{
		return false;
	}
};
function allgood($array){
	$alpha = true; // true unless something changes it
	// $output .= '<pre>';
	// print_r($array);
	// $output .= '</pre>';
	foreach($array as $key => $value){
		if (ctype_alnum($value)) {
			if(debug){$output .= 'Field(' . $key . ') is completely alphanumeric.<br/>';};
			if(strlen($value) < 1){
				$output .= '<b>Field too short!</b>';
				return false;
			}
		}else{
			if(ctype_alnum(str_replace('.', 'DOT', str_replace('@', 'AT', $value)))){
				$alpha = true;
			}else{
				$alpha = false;
			}
			if(empty($value)){
				$output .= '<b>Field Empty!</b><br/>';
			}else{
				$output .= 'Fields may only contain alphanumeric characters!<br/>';
				if(debug){$output .= 'Field(<b>' . $key . '</b>) is not completely alphanumeric.<br/>';};
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
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "SELECT $column_name FROM users WHERE name = '$user'";
	$result = mysqli_query($db, $query);
	$array = mysqli_fetch_array($result);
	$length = strlen($array[0]);
	$value = $array[0];
	// if(debug){$output .= 'Field Value: "' . $value . '"<br/>';};
	// if(debug){$output .= 'Length: ' . $length . '<br/>';};
	return $value;
};
function read_hash($user){
	include 'db.php';
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
	include 'db.php';
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
	if(debug){$output .= 'Running Hash Function with <b>' . $iter . '</b> iterations<br/>';};
	$i = 0;
	$hash = hash($algo, $salt . $userinput);
	// if(debug){$output .= '<br/><b>Input:</b> ' . $userinput . ' <b>Salt:</b> ' . $salt . ' <b>Hash:</b> ' . $hash . '<br/>';};
	while($i <= $iter){
		if($i % 100 == 0){ // to create variation
			$hash = str_rot13($hash);
		}else{
			$hash = strrev($hash);
		}
		$hash = hash($algo, $hash);
		// if(debug){$output .= '[' . $i . '](' . $algo . ')' . '->' . $hash . '<br/>';};
		$i++;
	}
	if(debug){$output .= '<b>Final Hash-></b>' . $hash . '<br/>';};
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
		return stripslashes($str);
	}else{
		return $str;
	}
};
function validate($username, $password){
	include 'db.php';
	$iterations = 100000;
	if($hash_fromDB = read_hash($username)){
		if(debug){$output .= '<b>Hash read from Database:</b> ' . $hash_fromDB . '<br/>';};
	}else{
		if(debug){$output .= 'Reading hash failed!<br/>';};
	}
	if($salt_fromDB = read_salt($username)){
		if(debug === true){$output .= 'Salt read from Database: ' . $salt_fromDB . '<br/>';};
	}else{
		if(debug){$output .= 'Reading salt failed!<br/>';};
	}
	if(hash_equals($hash_fromDB, $final_hash = create_hash(create_hash($password, $salt_fromDB, 'ripemd320', $iterations), $salt_fromDB, 'whirlpool', $iterations))){
		if(debug){$output .= '<b>Final Hash Generated:</b> ' . $final_hash . '<br/> <b>Hash(from DB):</b> ' . $hash_fromDB . '<br/>';};
		if(debug){$output .= 'Hashes Match!<br/>';};
		return true;
	}else{
		if(debug){$output .= 'Hashes do NOT match!<br/>';};
		if(debug === true){$output .= '<b>Final Hash Generated:</b> ' . $final_hash . '<br/> <b>Hash(from DB):</b> ' . $hash_fromDB . '<br/>';};
		return false;
	}
};
?>