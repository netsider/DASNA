<?php
require_once 'functions.php';
if(save_content($_POST['page'], htmlentities($_POST['data']))){
	echo json_encode('Saved');
}
?>