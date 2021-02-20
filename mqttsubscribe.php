<?php

require(__DIR__."/phpMQTT.php");
require_once(__DIR__."/config.php");
//$topic = [];

if (isset($config['cloudamqphost'])) {
    $host = $config['cloudamqphost'];
    $username = $config['cloudamqpuser'];
    $password = $config['cloudamqppass'];
    $port = $config['cloudamqpport'];
    $line1 = $config['cloudamqptopic'] . "/state_line" . "1"; // either one or 2 mayshe should be an array
    $line2 = $config['cloudamqptopic'] . "/state_line" . "2"; // either one or 2 mayshe should be an array
  } else {
  $host = '192.168.42.2';
  $username = "admin";
  $password = "admin";
  $port = 1883;
  $line1 = "obihai" . "/state_line" . "1"; // either one or 2 mayshe should be an array
  $line2 = "obihai" . "/state_line" . "2"; // either one or 2 mayshe should be an array
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