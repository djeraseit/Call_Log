<?php

error_reporting(E_ALL); 
ini_set( 'display_errors','1');

 // is SimpleXML installed
 if (!class_exists('SimpleXMLElement')){
    die('Sorry php-xml is not installed or php is not compiled with libxml!');
}
 // is cURL installed 
 if (!function_exists('curl_init')){
    die('Sorry cURL is not installed!');
}

$config = [];
$config['numverifykey'] = 'xxx';
$config['twiliosid'] = getenv("TWILIO_ACCOUNT_SID"); // use nvram
$config['twiliotoken'] = getenv("TWILIO_AUTH_TOKEN"); // use nvram
$config['obiusername'] = "admin";
$config['obipassword'] = "megalith";
$config['obihost'] = "192.168.42.2";
$config['pbtoken'] = 'o.mjCLA2hY2n5jVnwGwHrIDO76KccJtIbl';
$config['cloudamqphost'] = "eagle.rmq.cloudmqtt.com";
$config['cloudamqpport'] = "";
$config['cloudamqpuser'] = "rcndzvbo"; // default guest
$config['cloudamqppass'] = ""; // default guest
