<?php
$db = mysqli_connect('mysql', 'rootuser', 'newpass5506');
if (mysqli_connect_errno()){
	echo 'Failed to connect to MySQL - Error: ' . mysqli_connect_error();
}
?>