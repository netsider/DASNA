
<div id="container">
<table border="0">
<tr><td style="text-align: center;" colspan="2">Login:</td></tr>
<tr><td style="text-align: center;">Username:</td><td>
<?php 
echo "<input type='text' name='in-user'"; 
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
	echo '<tr><td colspan="2" style="text-align: center;"><input type="submit" name="in-submit"';
if($user_exist && $user_set){
		echo 'value="Login"class="button-success pure-button button-small"';
	}else{
		echo 'value="Next" class="button-success pure-button button-small"';
	}
	echo '/>';
?>
</td></tr><tr><td colspan='2'><div id="saved"><?php if(isset($output)){ echo $output;}; ?></div></td></tr>
</form></table>
</div>