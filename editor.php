<?php
require_once 'functions.php';
require_once 'login.php';
const database = 'dasna';
// const debug = true; // uncomment for debug
if($_SESSION['authenticated'] === true){
	echo '<html><head><meta http-equiv="Content-Type" content="text/html"; charset="iso-8859-1" />';
	if (debug){echo '<b>Username:</b> ' . $_SESSION['username'] . '<br/>';};
	$current_page = read_page($_SESSION['username']);
	if(debug){echo '<b>Current page:</b> ' . $current_page . '<br/>';};
	if($columns = read_column_array('content')){
		// if(debug){echo '<pre>';print_r($columns);echo '</pre>';};
		echo '<div id="dropdownDBdiv">Select Page:<form><select id="dropdownDB" onchange="set_DB()" multiple>';
		foreach($columns as $column){
			switch ($column){
			case "A":
				$col_name = get_pageName($column);
				break;
			case "B":
				$col_name = get_pageName($column);
				break;
			case "C":
				$col_name = get_pageName($column);
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
		echo '</select></form></div><br/>';
	}
	if($html = read_content($current_page)){
		echo '<div id="editordiv"><table width="100%" border="0"><tr><td colspan="2">';
		echo '<textarea class="ckeditor" name="editor1" id="editor1">' . $html . '</textarea></td></tr>';
		echo '<tr><td colspan="2"><div id="saved"></div><input type="button" id="publish" name="publish" value="Publish" style="display: none;" onclick="publishFunction()"></input>';
		echo '</td></tr></table></div>';
		echo '<br/><div id="backups"></div>';
	}else{
		echo 'Failed to read content!<br/>';
	}
}
?>
<script src="jquery.min.js"></script><script src="ckeditor.js"></script>
<title>DASNA Page Editing System</title>
<style>
#dropdownDBdiv{
	width: 150px;
	height: 40px;
	border-width:1px;
	margin-left: auto;
	margin-right; auto;
}
$editordiv{
	margin-left: auto;
	margin-right; auto;
}
</style>
</head><body>
<script>
<?php if($_SESSION['authenticated'] === true){ // So editor only displays if authenticated
	echo "var bodyEditor = CKEDITOR.replace('editor1',";
	echo '{';
	echo 'readOnly: false';
	echo '});';
}
?>
if(typeof bodyEditor != 'undefined'){
	var username = "<?php echo $_SESSION['username']; ?>";
	var page = "<?php echo $current_page; ?>";
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
}
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
			location.reload();
			},
		error: function(json_object){
            console.log("Error!");   
        }
    });
};
function saveFunction(dataIn){
	var current_page = page;
    var json_object = {"data": dataIn, "page": current_page};
    $.ajax({
        url: "ajax_save.php",
        data: json_object,
        dataType: 'json',
        type: 'POST',
        success: function(json_object){
			// var newdate = make_Date();
			document.getElementById("saved").style.color = "green";
			var btn = document.getElementById("publish");
			document.getElementById("publish").style.display = "inline";
			$("#saved").text(json_object + ' on ' + make_Date() + ' ');
			$("#saved").append(btn);
        },
        error: function(json_object){
            console.log("Error!"); 
        }
    });
};
function publishFunction(){
	ajaxGet("B");
	var dataIn = document.getElementById("editor1").value;
	var current_page = page;
    var json_object = {"data": dataIn, "page": current_page};
    $.ajax({
        url: "ajax_publish.php",
        data: json_object,
        dataType: 'json',
        type: 'POST',
        success: function(json_object){
			// var newdate = make_Date();
			document.getElementById("saved").style.color = "green";
			var btn = document.getElementById("publish");
			document.getElementById("publish").style.display = "inline";
			document.getElementById("publish").style.fontweight = "bolder";
			$("#saved").text(json_object + ' on ' + make_Date() + ' ');
			$("#saved").append(btn);
        },
        error: function(json_object){
            console.log("Error!"); 
        }
    });
};
function make_Date(){
	var date = new Date();
	var options = {
		weekday: "long", year: "numeric", month: "short",
		day: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"
	};
	var newdate = date.toLocaleTimeString("en-us", options);
	return newdate;
};
function ajaxGet(type){
	var json_object = {"type": type};
	$.ajax({
        url: "ajax_get.php",
        data: json_object,
        dataType: 'json',
        type: 'POST',
        success: function(json_object){
			$("#backups").text(json_object);
        },
        error: function(json_object){
            console.log("Error!"); 
        }
    });
};
</script>
</body>
</html>