<?php
require_once 'functions.php';
require_once 'login.php';
const database = 'dasna';
$show = true;
if($_SESSION['authenticated'] === true){
	echo '<html><head><meta http-equiv="Content-Type" content="text/html"; charset="iso-8859-1" />';
	echo '<link rel="stylesheet" href="pure.min.css"><script src="jquery.min.js"></script><script src="ckeditor.js"></script>';
	$current_page = read_page($_SESSION['username']);
	$debug = read_debug($_SESSION['username']);
	if($show){$output .= '<b>Current page:</b> ' . $current_page . '<br/>';};
	if($show){$output .= '$_SESSION[id]: ' . $_SESSION['id'] . '<br/>';};
	if($columns = read_column_array('content')){
		// if($show){echo '<pre>';print_r($columns);echo '</pre>';};
		echo '<div id="container" style="width: 75%;" class="center"><div id="dropdownDBdiv" style="float: left;">Select Section:<form><select id="dropdownDB" onchange="set_DB()">';
		foreach($columns as $column){
			$where = "section = '$column'";
			switch ($column){
			case "A":
				$col_name = select_from('pagename', 'content', $where);
				break;
			case "B":
				$col_name = select_from('pagename', 'content', $where);
				break;
			case "C":
				$col_name = select_from('pagename', 'content', $where);
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
		echo '</select></form></div><div id="dropdownDBdiv">Debugging<br/><form><input type="checkbox" name="check_debug" onchange="debugFunction()" id="check_debug"';
		if($debug === "1"){
			echo ' checked="checked"';
		}
		echo '></input></form></div></div></div>';
	}
	if($html = read_content($current_page)){
		echo '<div id="editordiv" class="center blackbox" style="position: relative;margin-top: -2px;"><table width="100%" border="0"><tr><td colspan="2">';
		echo '<textarea class="ckeditor" name="editor1" id="editor1">' . html_entity_decode($html) . '</textarea></td></tr>';
		echo '<tr><td colspan="2"><div id="saved" style="display: table-cell;vertical-align: middle;">&nbsp</div><input type="button" id="publish" name="publish" value="Backup Current Copy of this Page" style="display: none;margin-left: 3px;margin-top: 2px;" onclick="publishFunction()" class="pure-button pure-button-primary button-xsmall"></input>';
		echo '</td></tr></table></div>';
		echo '<center><div id="output" class="center blackbox"><b>Page output:</b>' . $output . '</div></center>';
		echo '<div id="livechanges" class="center blackbox"><span>All changes being made are <strong>live</strong> and will reflect on the <a href="http://dasna.net/beta.php">beta home page</a>.  There is also a <a href="http://dasna.net/ssl/editor.php">beta editor</a>.</span></div>';
		echo '<div id="backups" class="center blackbox">&nbsp</div>';
		echo '<div class="center blackbox">You can view the sourceocode for this on github: http://github.com/netsider/DASNA</div>';
	}else{
		echo 'Failed to read content!<br/>';
	}
}
?>
<title>DASNA Page Editing System</title>
<style>
#dropdownDBdiv{
	text-align: center;
	width: 145px;
	height: 40px;
	border-width: 1px;
	border: 1px solid black;
	border-bottom: 0px;
	padding: 2px;
	display: inline-block;
	left: -3px;
	position: relative;
}
#dropdownDBdiv:nth-child(2){
	border-left: 0px;
}
#saved{
	border-top: 1px solid black;
}
#output{
	text-align: left;
}
.blackbox{
	width: 75%;
	border-style: solid;
	borer-color: black;
	border-width: 1px;
	padding: 2px;
	margin-top: 2px;
}
.center{
	margin-left: auto;
	margin-right: auto;
}
.left{
	float: left;
}
.right{
	float: right;
}
.border{
	border-style: solid;
	borer-color: black;
	border-width: 1px;
}
#container{
	width: 100%;
}
@media (min-width: 300px) and (max-width: 800px) {
	#container{
		margin: auto;
		max-width: 600px;
	}
}
table{margin: 0 auto;}
.button-success {color: white;background: rgb(28, 184, 65);}
.button-small{font-size: 85%;}
.button-xsmall{font-size: 70%;}
</style>
</head><body onload="init()">
<script>
var username = "<?php echo $_SESSION['username']; ?>";
var page = "<?php echo $current_page; ?>";
<?php if($_SESSION['authenticated'] === true){ // So editor only displays if authenticated
	echo "var bodyEditor = CKEDITOR.replace('editor1',";
	echo '{';
	echo 'readOnly: false';
	echo '});';
	}
?>
function init(){
	if(typeof bodyEditor != 'undefined'){
		debugFunction();
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
};
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
			var btn = document.getElementById("publish");
			document.getElementById("publish").style.display = "inline-block";
			$("#saved").text(json_object + ' on ' + make_Date() + '.');
			$("#saved").append(btn);
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
		var output = '';
		output += '<table border="1" width="100%">';
		for (var property in json_object) {
			// output += "<tr><td><b>" + json_object[property][0] + "</b></td></tr>";
			for(var i in json_object[property]){
				output += "<tr><td><b>" + json_object[0][i] + "</b></td><td><i>" + json_object[1][i] + "</i></td></tr>";
			}
		}
		output += '</table>';
		$("#backups").html(output);
		// $('body').append( print(json_object) );
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
function debugFunction(){
	if($('#check_debug').is(":checked")){
		console.log("Checkbox is checked.");
		set_debug("1");
		document.getElementById("output").style.display = "inline-block";
	}else{
		set_debug("0");
		document.getElementById("output").style.display = "none";
		// console.log("Checkbox is not checked.");
	}
};
function set_debug(data){
	var user = username;
    var json_object = {"user": user, "data": data};
    $.ajax({
        url: "ajax_save_debug.php",
        data: json_object,
        dataType: 'json',
        type: 'POST',
        success: function(json_object){
			console.log("Returned AJax!");
        },
        error: function(json_object){
            console.log("Error!"); 
        }
    });
};
var print = function(o){
    var str='';
    for(var p in o){
        if(typeof o[p] == 'string'){
            str+= p + ': ' + o[p]+'; </br>';
        }else{
            str+= p + ': { </br>' + print(o[p]) + '}';
        }
    }
    return str;
}
</script>
</body>
</html>