<?php
require_once 'functions.php';
if(save_to_DB($_POST['user'], 'debug', $_POST['data'])){
	echo json_encode('DEBUG');
}
?>