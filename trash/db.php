<?php
$db = mysqli_connect('mysql', 'rootuser', '');
mysqli_select_db($db, 'dasna');
if (mysqli_connect_errno()){
	echo 'Failed to connect to MySQL - Error: ' . mysqli_connect_error();
}
?>