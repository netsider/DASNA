<?php
const debug = true;
function read_all_into_array($table){
	include 'db.php';
	mysqli_select_db($db, 'dasna');
	$query = "SELECT * FROM `$table`";
	$result = mysqli_query($db, $query);
	$column_array = array();
	while($array = mysqli_fetch_array($result)){
		// if(debug){echo $array[0] . $br;};
		$column_array[0][] = $array[0];
		$column_array[1][] = $array[1];
	}
	// if(debug){echo '<pre>';print_r($column_array[0]);print_r($column_array[1]);echo '</pre>';};
	return $column_array;
};
if(is_array($a = read_all_into_array('backups'))){
	if($_POST['type'] === "B"){
		echo json_encode($a[0]);
	}
	if($_POST['type'] === "A"){
		echo json_encode($a[1]);
	}
}else{
	echo json_encode('Fail');
}
?>