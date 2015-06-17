<?php
	include 'db.php';
	mysqli_select_db($db, 'dasna');
	$user = $_POST['data'];
	$query = "SELECT db FROM users WHERE name = '$user'";
	if($result = mysqli_query($db, $query)){
		$array = mysqli_fetch_array($result);
		echo json_encode($array[0]);
	}
?>