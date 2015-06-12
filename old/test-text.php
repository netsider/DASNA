<?php
	$from_add = "mailserver@dasna.net";
	$to_add = "4434972008@vtext.com"; 
	$subject = 'Validation';
	$message = 'Validation Code 3456';
	$headers = "From: <$from_add>" . "\n";
	$headers .= "Reply-To: $from_add" . "\n";
	$headers .= "Return-Path: $from_add" . "\n";
	$headers .= "MIME-Version: 1.0" . "\n";
	$headers .= "Content-type:text/plain;charset=UTF-8" . "\n";
	if(mail($to_add,$subject,$message,$headers,"-f admin@dasna.net")) 
	{
		$msg = "Mail sent OK";
	} 
	else 
	{
 	   $msg = "Error sending email!";
	}
	echo $msg;
	echo '9';
?>