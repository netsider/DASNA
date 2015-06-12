<?php
require_once 'functions.php';
const database = 'dasna';
echo '<br/><center>Welcome to the DASNA Page Editing System<br/></center><br/>';
if($A = read_content('A')){
	echo '<center>';
	echo '<table width="20%" border="1">';
	echo '<tr><td colspan="2"><center>Edit HTML</center></td></tr>';
	echo '<form action="editor.php" method="POST">';
	echo '<tr><td colspan="2">';
	echo '<textarea rows="30" cols="80" spellcheck="true" id="editor" onkeydown="myFunction()">' . htmlspecialchars($A) . '</textarea>';
	echo '</td></tr>';
	echo '<tr><td colspan="2"><input type="submit" name="in-submit" value="Login" /></td></tr>';
	echo '</form></table></center>';
	echo '<br/></center>';
}else{
	echo 'Failed to read content from database!<br/>';
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