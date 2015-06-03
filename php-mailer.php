<?php
error_reporting(E_ALL);ini_set('display_errors',1);
date_default_timezone_set("America/New_york");
require('class.phpmailer.php');
require('class.smtp.php');
clearstatcache();
if(ini_get('allow_url_fopen')) {
   echo 'Fopen on!<br/>';
}else{
	echo 'Fopen off!<br/>';
}
echo (extension_loaded('openssl')?'OpenSSL extension loaded!':'SSL not loaded')."<br/>";
$support = 'dasnashire@yahoo.com';
// echo shell_exec("sudo setsebool httpd_can_network_connect=1");
$mail = new PHPMailer();
// $mail->CharSet = 'UTF-8';
$mail->IsSMTP();
$mail->ContentType = 'text/plain';  
$mail->IsHTML(false);
$mail->SMTPDebug = 4;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls'; 
$mail->Host = 'smtp.mail.yahoo.com';
$mail->Port = 587;
$mail->Username = $support;
$mail->Password = ''; 
$mail->From = $support;
$mail->FromName = $support;
$mail->Subject = 'Test Message';
$mail->Body = 'Hello World';
$mail->AddAddress('4434972008@vtext.com');
$mail->AddCC('rdoubleoc@aol.com');
$mail->AddCC('rdoubleoc@gmail.com');
echo 'Version 1.28<br/>'; 
echo 'Attempting to send text message... <br/>';
// echo $mail->ErrorInfo;
if(!$mail->Send()){
    echo 'Message was not sent';
    echo 'Mailer error: ' . $mail->ErrorInfo;
}
else{
    echo 'Message has been sent.<br/>';
}
echo $mail->Host . '<br/>';
echo $mail->Username . '<br/>';
echo $mail->Port . '<br/>';
echo '<pre>';
var_export($_REQUEST);
echo '</pre>';
?>