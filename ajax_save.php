<?php
function save_content($user, $data){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "UPDATE content SET text = '$data' WHERE section = '$user'";
	if(mysqli_query($db, $query)){
		return true;
	}else{
		return false;
	}
};
$post_data = $_POST['data'];
save_content('A', $post_data);
echo json_encode('success');
?>