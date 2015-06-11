<?php
const database = 'dasna';
echo '<br/><center>Welcome to the DASNA Page Editing System<br/></center>';
function read_content($section){
	include 'db.php';
	mysqli_select_db($db, database);
	$query = "SELECT data FROM content WHERE section = '$section'";
	$result = mysqli_query($db, $query);
	$array = mysqli_fetch_array($result);
	$length = strlen($array[0]);
	$value = $array[0];
	if(debug){echo 'Field Value: "' . $value . '"<br/>';};
	if(debug){echo 'Length: ' . $length . '<br/>';};
	return $value;
};

if($A = read_content('A')){
	echo '<center>';
	echo '<table width="20%" border="1">';
	echo '<tr><td colspan="2">Edit HTML</td></tr>';
	echo '<form action="editor.php" method="POST">';
	echo '<tr><td colspan="2">';
	echo '<textarea rows="30" cols="80" spellcheck="true" id="editor" onkeydown="myFunction()">' . htmlspecialchars($A) . '</textarea>';
	echo '</td></tr>';
	echo '<tr><td colspan="2"><input type="submit" name="in-submit" value="Login" /></td></tr>';
	echo '</form></table></center>';
	echo '<br/></center>';
}else{
	echo 'Failed!<br/>';
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset="iso-8859-1" />
<script src="jquery.min.js"></script>
<title>1</title></head>
<body>
<div id="saved"></div>
<script>
function myFunction(){
	var div = document.getElementById("editor");
    var myData = div.value;
    var json_object = {"data": myData};
	var output = '';
	for (var property in json_object) {
	output += property + ': ' + json_object[property]+'; ';
	}
	console.log(output); // Outputs HTML from form successfully
    $.ajax({
        url: "ajax_save.php",
        data: json_object,
        dataType: 'json',
        type: 'POST',
        success: function(json_object){
			$("#saved").text(json_object);
            console.log("Saved");
        },
        error: function(json_object){
            console.log("Error!");   
        }
    });
};
</script>
</body>
</html>
<?php
	print_r($_POST);
?>