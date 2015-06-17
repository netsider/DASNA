<?php
	include 'db.php';
	mysqli_select_db($db, 'dasna');
	$user = $_POST['user'];
	$page = $_POST['page'];
	$query = "UPDATE users SET db = '$page' WHERE name = '$user'";
	if($result = mysqli_query($db, $query)){
		$array = mysqli_fetch_array($result);
		echo json_encode($array[0]);
	}
?>