<?php
const database = 'dasna';
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
function pick_carrier($string){
		switch ($string){
			case "verizon":
				$carrier = '@vzwpix.com';
				break;
			case "att":
				$carrier = '@mms.att.net';
				break;
			default:
				$carrier = '@vtext.com';
		}
		return $carrier;
}
?>