<?php
function read_all_into_array($table){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "SELECT * FROM `$table`";
	$result = mysqli_query($db, $query);
	$column_array = array();
	while($array = mysqli_fetch_array($result)){
		// if(debug){echo $array[0] . $br;};
		$column_array[] = $array[0];
	}
	return $column_array;
};
if(read_all_into_array('backups')){
	echo json_encode('Backups');
}
?>