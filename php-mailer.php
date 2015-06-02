<?php
error_reporting(E_ALL ^ E_NOTICE);
include('class.phpmailer.php');
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl'; 
$mail->Host = 'smtp.mail.yahoo.com';
$mail->Port = 465;
$mail->Username = 'admin@dasna.net';
$mail->Password = 'SDxv5AR428XHRJ63z%6C5N6lW9^1u4X8'; 
$mail->From = 'mailserver@dasna.net';
$mail->FromName = 'mailserver@dasna.net';
// $mail->AddAddress('rdoubleoc@aol.com');
$mail->AddAddress('rdoubleoc@gmail.com');
$mail->AddAddress('14434972008@vtext.com');
$mail->Subject = "Test Message";
$mail->Body = 'Hello World';
echo 'Attempting to send text message... <br/>';
echo 'Version 1.15<br/>'; 
if(!$mail->Send()){
    echo 'Message was not sent';
    echo 'Mailer error: ' . $mail->ErrorInfo;
}
else{
    echo 'Message has been sent.';
}
?>