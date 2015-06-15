<?php
require_once 'functions.php';
const database = 'dasna';
if($_SESSION['authenticated'] === true){
	echo '<br/><center>Welcome to the DASNA Page Editing System<br/></center><br/>';
	if($A = read_content('A')){
		echo '<center>';
		echo '<table width="75%" border="1"><form>';
		echo '<tr><td colspan="2"><center><span style="font-size: 11px;">Edit HTML</span></center></td></tr>';
		echo '<tr><td colspan="2">';
		echo '<textarea class="ckeditor" name="editor1" id="editor1">' . $A . '</textarea>';
		echo '</td></tr>';
		echo '<tr><td colspan="2">';
		echo '<div id="saved"></div>';
		echo '</td></tr>';
		echo '</table></form></center>';
		echo '<br/></center>';
	}else{
		echo 'Failed to read content from database!<br/>';
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"; charset="iso-8859-1" />
<script src="jquery.min.js"></script>
<script src="ckeditor.js"></script>
<title>DASNA Page Editing System</title>
</head>
<body>
<script>
var bodyEditor = CKEDITOR.replace('editor1',
{
    readOnly: false
	customConfig: ''
});


bodyEditor.on('mode', function () {
    if (this.mode == 'source') {
        var editable = bodyEditor.editable();
        editable.attachListener(editable, 'input', function () {
			console.log("Change Occured!");
        });
    }
});
bodyEditor.on('change', function () {
    console.log("Change Occured!");
	var data = CKEDITOR.instances.editor1.getData();
	saveFunction(data);
});
function saveFunction(dataIn){
	var output = {};
	// var element = document.getElementById("editor1");
    // var myData = element.value;
	var myData = dataIn;
    var json_object = {"data": myData};
	for (var property in json_object) {
	output += property + ': ' + json_object[property];
	}
	console.log(output);
    $.ajax({
        url: "ajax_save.php",
        data: json_object,
        dataType: 'json',
        type: 'POST',
        success: function(json_object){
		var date = new Date();
		var options = {
			weekday: "long", year: "numeric", month: "short",
			day: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"
		};
			var newdate = date.toLocaleTimeString("en-us", options);
			document.getElementById("saved").style.color = "green";
			$("#saved").text(json_object + ' on ' + newdate);
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