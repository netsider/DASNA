<?php
error_reporting(E_ALL);ini_set('display_errors',1);
/**
 * This uses the SMTP class alone to check that a connection can be made to an SMTP server,
 * authenticate, then disconnect
 */
require 'PHPMailerAutoload.php';
require('class.smtp.php');
$smtp = new SMTP;

//Enable connection-level debug output
$smtp->do_debug = SMTP::DEBUG_CONNECTION;

try {
//Connect to an SMTP server
    if ($smtp->connect('smtp.mail.yahoo.com', 587)) {
        //Say hello
        if ($smtp->hello('localhost')) { //Put your host name in here
            //Authenticate
            if ($smtp->authenticate('dasnashire', '')) {
                echo "Connected ok!";
            } else {
                throw new Exception('Authentication failed: ' . $smtp->getLastReply());
            }
        } else {
            throw new Exception('HELO failed: '. $smtp->getLastReply());
        }
    } else {
        throw new Exception('Connect failed');
    }
} catch (Exception $e) {
    echo 'SMTP error: '. $e->getMessage(), "\n";
}
//Whatever happened, close the connection.
$smtp->quit(true)
?>