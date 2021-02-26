<?php

/**
* Megalith Technologies
*
* @category VoIP
* @author Theodis Butler
* @copyright Copyright (c) 2021 Megalith Technologies (https://megalithtechnologies.com)
* @license https://www.gnu.org/licenses/gpl-3.0.en.html
*
* Call Blocker for stopping annoying, spam and robocalls on Obihai Devices
* Copyright (C) 2021 Theodis Butler
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
*/

set_time_limit (0);

require(__DIR__."/phpMQTT.php");
$config = require __DIR__.'/config.php';
//$topic = [];

if (isset($config['cloudamqp']['host'])) {
    $host = $config['cloudamqp']['host'];
    $username = $config['cloudamqp']['credentials']['username'];
    $password = $config['cloudamqp']['credentials']['password'];
    $port = $config['cloudamqp']['port'];
    $line1 = $config['cloudamqp']['topic'] . "/state_line" . "1"; // either one or 2 mayshe should be an array
    $line2 = $config['cloudamqp']['topic'] . "/state_line" . "2"; // either one or 2 mayshe should be an array
  } else {
      die('Missing variables. Please check config file.');
  }
  $topic = array($line1,$line2);

//var_dump($topic);
$mqtt = new bluerhinos\phpMQTT($host, $port, "obihai2mqtt_".rand());

if(!$mqtt->connect(true,NULL,$username,$password)){
  exit(1);
}

//currently subscribed topics
//$topics[$topic] = array("qos"=>0, "function"=>"procmsg");
$topics[$line1] = array("qos"=>0, "function"=>"procmsg");

//var_dump($topics);

$mqtt->subscribe($topics,0);

while($mqtt->proc()){
}

$mqtt->close();
function procmsg($topic,$msg){
  echo "Msg Recieved: $msg" . PHP_EOL;
  echo 'Date Recieved: ' . date('r') . PHP_EOL;
	echo "Topic: {$topic}" . PHP_EOL;
}