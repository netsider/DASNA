<?php
function save_content($section, $data){
	include 'db.php';
	mysqli_select_db($db, 'dasna');
	$query = "INSERT INTO $section (data) VALUES ('$data')";
	if(mysqli_query($db, $query)){
		return true;
	}else{
		return false;
	}
};
if(save_content('middlecolumn', htmlentities($_POST['data']))){
	header("refresh:5;url=login.php");
	echo json_encode('Published');
}else{
	echo '<pre>';
	var_export(_$POST);
	echo '</pre>';
}
?>