<?php
session_start();include_once('options.php');
session_regenerate_id(true);$sid = session_id(); // To prevent session fixation
$TABLE = 'users';$database = 'dasna';
if($_POST){ // If submit button has been pressed
	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre><br/>';
	function allgood(){
		$minlength = 1;
		$maxlength = 100;
		$fc = '<font color="red">';
		$efc = '</font>';
		foreach($_POST as $key => $value){
			if(strlen($value) < $minlength){
				$a = $fc . 'Length of <b>' . $key . '</b> is <b>' . strlen($value) . '</b>' . $efc;
				echo $a;
				return false;
			}
			if(strlen($value) > $maxlength){
				echo $a;
				return false;
			}
			if (ctype_alnum($value)) {
				echo '<font color="green">The field(<b>' . $key . '</b>) is completely letters and/or digits.<br/>' . $efc;
			} else {
				echo $fc . 'The field(<b>' . $key . '</b>) is not completely letters and/or digits.<br/>' . $efc;
				return false;
			}
	
		}
	return true;
	};
	if (allgood()){
		$username = $_POST['in-user'];
		$password = $_POST['in-pass'];
		include_once('db.php');
		mysqli_select_db($db, $database);
		$result = mysqli_query($db, "SELECT * FROM users");
		while($row = mysqli_fetch_array($result)){
		if($row[1] === $username){
			echo 'User <b>' . $row[1] . '</b> found!';
			$user_exist = true;
		}
		}
		// echo '<pre>';
		// print_r($result);
		// echo '</pre><br/>';
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