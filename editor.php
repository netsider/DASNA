<?php
require_once 'functions.php';
const database = 'dasna';
echo '<html><head><meta http-equiv="Content-Type" content="text/html"; charset="iso-8859-1" />';
if($_SESSION['authenticated'] === true){
	if (debug){echo 'Username: ' . $_SESSION['username'];};
	if(debug){echo 'Authenticated!'; };
	$current_page = read_page($_SESSION['username']);
	echo 'current page: ' . $current_page . $br;
	echo '<center>';
	if($columns = read_column_array('content')){
		if(debug){echo '<pre>';print_r($columns);echo '</pre>';};
	};
	echo '<div id="selectDB_form" style="width:200px;border-style:solid;border-width:1px;">';
	echo 'Select page to edit:<br/>';
	echo '<form><select id="dropdownDB">';
	foreach($columns as $column){
	switch ($column){
	case "A":
		// $col_name = 'Left Column';
		$col_name = $column;
		break;
	case "B":
		// $col_name = 'Right Column';
		$col_name = $column;
		break;
	case "C":
		// $col_name = 'Middle';
		$col_name = $column;
		break;
	}
		if($current_page === $column){
			echo '<option value="' . $column . '" selected="selected">';
			echo $col_name;
		}else{
			echo '<option value="' . $column . '">';
			echo $col_name;
		}
		echo '</option>';
	}
	echo '</select>';
	echo '<input type="button" id="changeDB" onclick="set_DB()" value="Change Page"></input>';
	echo '</div>';
	echo '</form></center>';
	if($A = read_content($current_page)){
		echo '<center>';
		echo '<div id="editordiv" style="width: 75%;"><table width="100%" border="0">';
		echo '<tr><td colspan="2">';
		echo '<form action="ajax_publish.php"><textarea class="ckeditor" name="editor1" id="editor1">' . $A . '</textarea></form>';
		echo '</td></tr>';
		echo '<tr><td colspan="2">';
		echo '<div id="saved" style="font-weight: bold;"></div>';
		echo '</td></tr>';
		echo '<tr><td colspan="2"><div id="motd"></div></td></tr>';
		echo '</table></div>';
		echo '<br/></center>';
	}else{
		echo 'Failed to read content from database!<br/>';
	}
}
?>
<script src="jquery.min.js"></script>
<script src="ckeditor.js"></script>
<title>DASNA Page Editing System</title>
</head>
<body>
<script>
var username = "<?php echo $_SESSION['username']; ?>";
var page = "<?php echo $current_page; ?>";
function set_DB(){
	var output = {};
	var element = document.getElementById("dropdownDB");
    var myData = element.value;
	var user = username;
    var json_object = {"user": user, "page": myData};
    $.ajax({
        url: "set-db.php",
        data: json_object,
        dataType: 'json',
        type: 'POST',
        success: function(json_object){
			document.getElementById("saved").style.color = "green";
			$("#saved").text(json_object);
            console.log("Saved");
			for (var property in json_object) {
				output += property + ': ' + json_object[property];
			}
			console.log(output);
			location.reload();
			},
		error: function(json_object){
            console.log("Error!");   
        }
    });
};
function get_DB(){
	var output = {};
	var user = username;
    var json_object = {"data": user};
    $.ajax({
        url: "get-db.php",
        data: json_object,
        dataType: 'json',
        type: 'POST',
        success: function(json_object){
			$("#saved").text(json_object);
            console.log("Saved");
				for (var property in json_object) {
	output += property + ': ' + json_object[property];
	}
	console.log(output);
		return output;
        },
        error: function(json_object){
            console.log("Error!");   
        }
    });
};
$('#saved').html('&nbsp');
// $('#motd').text(" ");
var bodyEditor = CKEDITOR.replace('editor1',
{
    readOnly: false
});
bodyEditor.on('mode', function () {
    if (this.mode == 'source') {
        var editable = bodyEditor.editable();
        editable.attachListener(editable, 'input', function () {
			console.log("Change(mode) Occured!");
        });
    }
});
bodyEditor.on('change', function () {
    console.log("Change(change) Occured!");
	var data = CKEDITOR.instances.editor1.getData();
	saveFunction(data);
});
bodyEditor.on('save', function () {
    console.log("Change(save) Occured!");
	var data = CKEDITOR.instances.editor1.getData();
});
function saveFunction(dataIn){
	var output = {};
	var current_page = page;
	var new_page = get_DB();
	// var element = document.getElementById("editor1");
    // var myData = element.value;
	var myData = dataIn;
    var json_object = {"data": myData, "page": current_page, "newpage": new_page};
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