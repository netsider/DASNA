<?php
session_start();include_once('options.php');
session_regenerate_id(true);$sid = session_id(); // To prevent session fixation
$allgood = true;
$TABLE = 'users';$database = 'dasna';
if($_POST){ // If submit button has been pressed
	echo '<pre>';
	print_r($_POST);
	echo '</pre><br/>';
	foreach($_POST as $key => $value){
	if (ctype_alnum($value)) {
        echo '<font color="green">The field(<b>' . $key . '</b>) consists of all letters and/or digits.</font><br/>';
    } else {
		echo '<font color="red">The field(<b>' . $key . '</b>) does not consist of all letters and/or digits.</font><br/>';
		$allgood = false; // if any values contain non-alpha characters, set false to prevent any possible injection.
    }
	}
	if ($allgood){
		$username = $_POST['in-user'];
		$password = $_POST['in-pass'];
		include_once('db.php');
		mysqli_select_db($db, $database);
		$result = mysqli_query($db, "SELECT * FROM users");
		while($row = mysqli_fetch_array($result)){
		if($row[0] == $username){
		
		}
		}
		echo '<pre>';
		print_r($result);
		echo '</pre><br/>';
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