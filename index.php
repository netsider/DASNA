<?php
require('options.php');
session_regenerate_id(true);$sid = session_id(); // To prevent session fixation
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	<center>
	<table width='20%' border='1'>
	<tr><td colspan="2"><?php echo $sid; ?></td></tr>
	<form action='login.php' method='POST'>
	<tr><td>Username:</td><td><input type='text' name='in-user' /></td></tr>
	<tr><td>Password:</td><td><input type='password' name='in-pass' /></td></tr>
	<tr><td colspan='2'><input type='submit' name='in-submit' value='Login' /></td></tr>
	<input type="hidden" name="page_origin" value="12345">
	</form>
	</table>
	</center>
</body>
</html>