<?php
require_once 'functions.php';
require_once 'login.php';
const database = 'dasna';
const debug = false;
if($_GET['debug'] = true){
	echo 'DEBUG ON!';
	const debug = true;
}else{
	echo 'DEBUG OFF';
	const debug = true;
}
if($_SESSION['authenticated'] === true){
	echo '<html><head><meta http-equiv="Content-Type" content="text/html"; charset="iso-8859-1" />';
	if(debug){echo 'Authenticated!' . '<br/>'; };
	if (debug){echo 'Username: ' . $_SESSION['username'] . '<br/>';};
	$current_page = read_page($_SESSION['username']);
	if(debug){echo 'Current page: ' . $current_page . '<br/>';};
	if($columns = read_column_array('content')){
		// if(debug){echo '<pre>';print_r($columns);echo '</pre>';};
		echo '<center><div style="width: 150px;height: 40px;border-width:1px;">';
		echo 'Select Page: <form><select id="dropdownDB" onchange="set_DB()">';
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
		echo '</select></div></form>';
	}
	if($html = read_content($current_page)){
		echo '<table width="100%" border="0"><tr><td colspan="2">';
		echo '<textarea class="ckeditor" name="editor1" id="editor1">' . $html . '</textarea></td></tr>';
		echo '<tr><td colspan="2"><div id="saved" style="font-weight: bold;">&nbsp</div>';
		echo '<input type="button" id="publish" name="publish" value="Publish" style="display: none;"></input></td></tr></table></center>';
	}else{
		echo 'Failed to read content!<br/>';
	}
}
?>
<script src="jquery.min.js"></script>
<script src="ckeditor.js"></script>
<title>DASNA Page Editing System</title>
</head>
<body>
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
	bodyEditor.on('save', function () {
		console.log("Change(save) Occured!");
		var data = CKEDITOR.instances.editor1.getData();
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
			var newdate = make_my_Date();
			document.getElementById("saved").style.color = "green";
			document.getElementById("saved").style.display = "inline";
			$("#saved").text(json_object + ' on ' + newdate);
        },
        error: function(json_object){
            console.log("Error!"); 
        }
    });
};
function make_my_Date(){
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