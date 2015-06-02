<?php
	$msg="";
	$from_add = "webserver@dasna.net";
	$to_add = "rdoubleoc@gmail.com";
	$subject = "Test Subject";
	$message = "Test Message";
	$headers = "From: $from_add \r\n";
	$headers .= "Reply-To: $from_add \r\n";
	$headers .= "Return-Path: $from_add\r\n";
	$headers .= "X-Mailer: PHP \r\n";
	if(mail($to_add,$subject,$message,$headers)) 
	{
		$msg = "Mail sent OK";
	} 
	else 
	{
 	   $msg = "Error sending email!";
	}
	echo $msg;
?>