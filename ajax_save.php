<?php
function save_content($section, $data){
	include 'db.php';
	mysqli_select_db($db, 'dasna');
	$query = "UPDATE content SET data = '$data' WHERE section = '$section'";
	if(mysqli_query($db, $query)){
		return true;
	}else{
		return false;
	}
};
if(save_content('A', htmlentities($_POST['data']))){
	echo json_encode('Saved');
}
?>