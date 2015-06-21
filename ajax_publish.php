<?php
function insert_into($table, $values){
	include 'db.php';
	mysqli_select_db($db, 'dasna');
	$query = "INSERT INTO $table (data) VALUES ('$values')";
	if(mysqli_query($db, $query)){
		return true;
	}else{
		return false;
	}
};
if(insert_into('middlecolumn', htmlentities($_POST['data']))){
	echo json_encode('Published');
}
?>