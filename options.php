<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 'On');
date_default_timezone_set("America/New_york"); // sometimes needed for certain things to work correctly
ini_set('session.hash_function','whirlpool'); // Sets hash used to generate session_id's
?>