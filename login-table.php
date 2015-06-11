<table width='20%' border='1'>
<tr><td colspan="2">Login</td></tr>
<?php 
echo "<tr><td>Username:</td><td><input type='text' name='in-user'"; 
if(!empty($username) && isset($username)){
	echo ' value="' . addslashes($username) . '"';
	echo ' disabled';
}
echo '/></td></tr>';
if($user_exist){
	echo "<tr><td>Password:</td><td><input type='password' name='in-pass'";
	if(!empty($password) && isset($password)){
		echo ' value="' . addslashes($password) . '"';
		echo ' disabled';
	}
	echo '/></td></tr>';
}
	echo "<tr><td colspan='2'><input type='submit' name='in-submit'";
if($user_exist && $user_set){
		echo 'value="Login"';
	}else{
		echo 'value="Submit"';
	}
	echo '/></td></tr>';
?>
<tr><td colspan='2'><?php if(isset($output)){ echo $output;}; ?></td></tr>
</form></table>