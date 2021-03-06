<?php
require_once 'functions.php';
require_once 'login.php';
const database = 'dasna';
$show = true; // comment for $show
if($_SESSION['authenticated'] === true){
	echo '<html><head><meta http-equiv="Content-Type" content="text/html"; charset="iso-8859-1" />';
	$current_page = read_page($_SESSION['username']);
	if($show){$output .= '<b>Current page:</b> ' . $current_page . '<br/>';};
	if($show){$output .= '$_SESSION[id]: ' . $_SESSION['id'] . '<br/>';};
	if($columns = read_column_array('content')){
		// if($show){echo '<pre>';print_r($columns);echo '</pre>';};
		echo '<div id="container" style="width: 75%;" class="center"><div id="dropdownDBdiv">Select Page:<form><select id="dropdownDB" onchange="set_DB()">';
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
		echo '</select></form></div></div>';
	}
	if($html = read_content($current_page)){
		echo '<div id="editordiv"><table width="100%" border="0"><tr><td colspan="2">';
		echo '<textarea class="ckeditor" name="editor1" id="editor1">' . html_entity_decode($html) . '</textarea></td></tr>';
		echo '<tr><td colspan="2"><div id="saved">&nbsp</div><input type="button" id="publish" name="publish" value="Publish" style="display: none;" onclick="publishFunction()"></input>';
		echo '</td></tr></table></div>';
		echo '<br/><div id="output"><b>Page output:</b>' . $output . '</div>';
	}else{
		echo 'Failed to read content!<br/>';
	}
}
?>
<script src="jquery.min.js"></script><script src="ckeditor.js"></script>
<title>DASNA Page Editing System</title>
<style>
.center{
	margin-left: auto;
	margin-right: auto;
}
#dropdownDBdiv{
	text-align: center;
	width: 150px;
	height: 40px;
	border-width: 1px;
	border: 1px solid black;
	border-bottom: 0px;
}
#saved{
	border-top: 1px solid black;
}
#editordiv{
	width: 75%;
	margin-left: auto;
	margin-right: auto;
	border-width: 1px;
	border-style: solid;
	borer-color: black;
}
#output{
	width: 75%;
	margin-left: auto;
	margin-right: auto;
	text-align: left;
	border-width: 1px;
	border-style: solid;
	borer-color: black;
	padding: 2px;
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
	document.getElementById("dropdownDB").disabled = true;
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
			document.getElementById("saved").style.color = "green";
			// var btn = document.getElementById("publish");
			// document.getElementById("publish").style.display = "inline";
			$("#saved").text(json_object + ' on ' + make_Date() + ' ');
			// $("#saved").append(btn);
        },
        error: function(json_object){
            console.log("Error!"); 
        }
    });
};
function publishFunction(){
	ajaxGet('A');
	var dataIn = document.getElementById("editor1").value;
	var current_page = page;
    var json_object = {"data": dataIn, "page": current_page};
    $.ajax({
        url: "ajax_publish.php",
        data: json_object,
        dataType: 'json',
        type: 'POST',
        success: function(json_object){
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
function ajaxGet(type){
	var json_object = {'type': type};
	$.ajax({
        url: 'ajax_get.php',
        data: json_object,
        dataType: 'json',
        type: 'POST',
        success: function(json_object){
			$("#backups").text(json_object[1]);
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
</script>
</body>
</html>