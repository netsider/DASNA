<?php
session_start();include_once('options.php');
session_regenerate_id(true);$sid = session_id(); // To prevent session fixation
$TABLE = 'users';$database = 'dasna';
if($_POST){ // If submit button has been pressed
	echo '<pre>';
	print_r($_POST);
	echo '</pre><br/>';
	function allgood($array){ // returns false if not alphanumeric
		$minlength = 1;
		$maxlength = 100;
		$fc = '<font color="red">';
		$efc = '</font><br/>';
		$a = $fc . 'Length of <b>' . $key . '</b> is <b>' . $length . '</b>' . $efc;
		foreach($array as $key => $value){
			$length = strlen($value);
			if($length < $minlength){
				echo $a;
				return false;
			}
			if($length > $maxlength){
				echo $a;
				return false;
			}
			if (ctype_alnum($value)) {
				echo '<font color="green">The field (<b>' . $key . '</b>) is completely alphanumeric.' . $efc;
			} else {
				echo $fc . 'The field (<b>' . $key . '</b>) is not completely alphanumeric.' . $efc;
				return false;
			}
	
		}
	return true;
	};
	if (allgood($_POST)){
		$username = $_POST['in-user'];
		$password = $_POST['in-pass'];
		include_once('db.php');
		mysqli_select_db($db, $database);
		$result = mysqli_query($db, "SELECT name FROM users");
		while($row = mysqli_fetch_array($result)){
			if($row[0] === $username){
				echo 'User <b>' . $username . '</b> found!';
				$user_exist = true;
			}
		}
		mysqli_close($db);
		if($user_exist){
			
		}else{
			echo 'User does not exist!';
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
</body>
</html>