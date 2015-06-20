<?php
require_once 'functions.php';
require_once 'login.php';
const database = 'dasna';
const debug = true;
if($_SESSION['authenticated'] === true){
	echo '<html><head><meta http-equiv="Content-Type" content="text/html"; charset="iso-8859-1" />';
	if (debug){echo 'Username: ' . $_SESSION['username'] . '<br/>';};
	if(debug){echo 'Authenticated!' . '<br/>'; };
	$current_page = read_page($_SESSION['username']);
	if(debug){echo 'Current page: ' . $current_page . '<br/>';};
	if($columns = read_column_array('content')){
		// if(debug){echo '<pre>';print_r($columns);echo '</pre>';};
		echo '<center><div style="width: 150px;height: 40px;border-style:solid;border-width:1px;margin-left: 0%;">';
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
		echo '<table width="100%" border="0">';
		echo '<tr><td colspan="2">';
		echo '<textarea class="ckeditor" name="editor1" id="editor1">' . $html . '</textarea>';
		echo '</td></tr>';
		echo '<tr><td colspan="2">';
		echo '<div id="saved" style="font-weight: bold;">&nbsp</div>';
		echo '</td></tr>';
		echo '</table></center>';
	}else{
		echo '<b>Failed to read content!</b>' . $br;
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