<?php

error_reporting(E_ALL); 
ini_set( 'display_errors','1');

$config = [];
$config['numverifykey'] = 'xxx';
$config['twiliosid'] = getenv("TWILIO_ACCOUNT_SID"); // use nvram
$config['twiliotoken'] = getenv("TWILIO_AUTH_TOKEN"); // use nvram
$config['obiusername'] = "admin";
$config['obipassword'] = "megalith";
$config['obihost'] = "192.168.42.2";
