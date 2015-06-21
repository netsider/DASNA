<?php
	$v = htmlentities($_POST['data']);
	$t = 'backups';
	include 'db.php';
	mysqli_select_db($db, 'dasna');
	$query = "INSERT INTO $t (data) VALUES ('$v')";
	if(mysqli_query($db, $query)){
		echo json_encode('Published');
	}
?>