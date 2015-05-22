<?php
session_start();error_reporting(E_ALL ^ E_NOTICE);date_default_timezone_set("America/New_york");$sid = session_id();
// echo $sid;
session_regenerate_id(true); // To prevent session fixation
?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "utf-8">
	<title>Login</title>
</head>
<body>
	<center>
	<table width='20%' border='1'>
	<tr><td colspan="2"><?php echo $sid; ?></td></tr>
	<form action='login.php' method='POST'>
	<tr><td>Username:</td><td><input type='text' name='in-user' /></td></tr>
	<tr><td>Password:</td><td><input type='password' name='in-pass' /></td></tr>
	<tr><td colspan='2'><input type='submit' name='in-submit' value='Login' /></td></tr>
	</table>
	</center>
</body>
</html>