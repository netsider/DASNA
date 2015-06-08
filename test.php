<?php
	// $new_num = NULL;
	for ($x = 0; $x < 10; $x++) {
		$int = mt_rand(0, 9);
		echo $i . '-->' . $int . '<br/>';
		$new_num .= $int;
	}
	echo '<br/>';
	echo '<b>' . $new_num . '</b>';
?>