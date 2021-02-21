<?php

/**
* Megalith Technologies
*
* @category VoIP
* @author Theodis Butler
* @copyright Copyright (c) 2021 Megalith Technologies (https://megalithtechnologies.com)
* @license https://www.gnu.org/licenses/gpl-3.0.en.html
*/

error_reporting(E_ALL); 
ini_set( 'display_errors','1');
date_default_timezone_set('America/Chicago'); 

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
$config['scheme'] = 'http';
$config['amqpurl'] = 'amqps://rcndzvbo:E-BaZornNc1uuSID1kjuVmzDsj1UrxVY@eagle.rmq.cloudamqp.com/rcndzvbo';
$config['twiliosid'] = getenv("TWILIO_ACCOUNT_SID"); // use nvram
$config['twiliotoken'] = getenv("TWILIO_AUTH_TOKEN"); // use nvram
$config['obiusername'] = "admin";
$config['obipassword'] = "megalith";
$config['obihost'] = "192.168.42.2";
$config['pbtoken'] = 'o.mjCLA2hY2n5jVnwGwHrIDO76KccJtIbl';
$config['cloudamqphost'] = "eagle.rmq.cloudamqp.com"; // or eagle-01.rmq.cloudamqp.com (not loadbalanced)
$config['cloudamqpport'] = "1883"; // 8883 for TLS (mqtt) 1883 for normal
$config['cloudamqpvhost'] = "rcndzvbo"; 
$config['cloudamqpuser'] = "rcndzvbo:rcndzvbo"; // (mqtt) 
$config['cloudamqppass'] = "E-BaZornNc1uuSID1kjuVmzDsj1UrxVY";
$config['cloudamqptopic'] = "obihai"; 
$config['youmailkey'] = "6946b98eb76a1781eb15ae89df024fce9d859a8167e7012b60fd89779627521fef4eea4cceb51f73";
$config['youmailsid'] = "c54d3629988a46019dc5a863ce81d55a";