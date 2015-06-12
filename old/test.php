<?php
	// $new_num = NULL;
	// for ($x = 0; $x < 10; $x++) {
		// $int = mt_rand(0, 9);
		// echo $i . '-->' . $int . '<br/>';
		// $new_num .= $int;
	// }
	// echo '<br/>';
	// echo '<b>' . $new_num . '</b>';
// if(!function_exists('hash_equals')) {
  // function hash_equals($str1, $str2) {
    // if(strlen($str1) != strlen($str2)){
      // return false;
    // }else{
      // $res = $str1 ^ $str2; 
      // $ret = 0;
      // for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
	  // echo $ret;
      // return !$ret;
    // }
  // }
// }
echo '4<br/>';
if(!function_exists('hash_equals')) {
  function hash_equals($str1, $str2){
    if(strlen($str1) != strlen($str2)){
      return false;
    }else{
    $string = $str1 ^ $str2;
    $return = 0;
	echo 'String: ' . $string . '<br/>';
	for($i = strlen($string) - 1;$i >= 0;$i--){
		echo '<b>' . ord($string[$i]) . '</b><br/>';
		$return |= ord($string[$i]); // Return's 0 if strings are the same, and the highest ASCII value of any letters, if not
	}
	return $return; 
    }
  };
}
echo '<br/>';
echo '<br/>' . hash_equals('AAAAAA', 'AAAABZ');
?>