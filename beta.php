<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> --> 
	<link rel="stylesheet" type="text/css" href="http://www.dasna.net/dasna_main.css" />
	<title>Development Association of Shire: North America</title>
	<meta name="keywords" content="DASNA, non-profit, ethiopia, development association, shire, endasilassie, alumni, tigrai, tigray, africa, library" />
	<meta name="alexaVerifyID" content="L92Wl15x0IcBCdsKwyvPlFGIrkQ" />
	<link rel="shortcut icon" href="http://www.dasna.net/favicon.ico" />
	<script src="http://www.dasna.net/jquery-1.4.2.min.js"></script>
	<script src="http://www.dasna.net/jquery.panelgallery2.js"></script>
	<script>
			$(function()
			{
				$('#slidecontainer').panelGallery({
					viewDuration: 6000,
					panelTransitionDuration: 40,
					transitionDuration: 50,
					boxSize: 35,
					panelWidth: 40,
					boxFadeDuration: 0,
					boxTransitionDuration: 5,
				});
			});
	</script>
    <style type="text/css">
    h2 {
      -moz-animation-duration: 3s;
      -webkit-animation-duration: 3s;
      -moz-animation-name: slidein;
      -webkit-animation-name: slidein;
    }
	#rightcolumn 
	{	
    height: 2000px;
	}
	#leftcolumn 
	{	
    height: 2000px;
	}
	#content 
	{	
    height: 2000px;
	}
    @-moz-keyframes slidein {
      from {
        margin-left:100%;
        width:300%
      }
      
      to {
        margin-left:0%;
        width:100%;
      }
    }
    
    @-webkit-keyframes slidein {
      from {
        margin-left:100%;
        width:300%
      }
      
      to {
        margin-left:0%;
        width:100%;
      }
    }
  </style>
<style>
#home
{
	font-weight : bold; 
}
</style>
</head>
<body>
    <div id="wrapper">
    <div id="header">
		<div id="logo"><a href="http://www.dasna.net/">Development Association of Shire</a></div>
    </div>
	<div class="headerphoto" id="slidecontainer" style="width:1000px;position:relative;">
	<?php include 'show2.html'; ?>	
	</div>    
	<div id="nav-bar">
     <?php include '/articles/nav.php'; ?>
    </div><div id="leftcolumn">	
	<?php
	include_once '/ssl/functions.php';
	if($a = read_content('A')){
		echo html_entity_decode($a);
	}
	?>
	</div>
    <div id="content">
	<h2>Welcome to Development Association of Shire</h2><span style="font-size:13px">
	<?php
	include_once '/ssl/functions.php';
	if($a = read_content('C')){
		echo html_entity_decode($a);
	}
	?>
	</div>
	<div id="rightcolumn">
	<?php
	include_once '/ssl/functions.php';
	if($a = read_content('B')){
		echo html_entity_decode($a);
	}
	?>
	</div>
	<div id="footer">
	<?php include 'end.php'; ?>
    </div>
    </div>
</body>
</html>