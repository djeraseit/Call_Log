<?php

require(__DIR__."/phpMQTT.php");
require_once(__DIR__."/config.php");

if (isset($config['cloudamqphost'])) {
    $host = $config['cloudamqphost'];
    $username = $config['cloudamqpuser'];
    $password = $config['cloudamqppass'];
    $port = $config['cloudamqpport'];
    $topic = $config['cloudamqptopic'];
  } else {
  $host = '192.168.42.2';
  $username = "admin";
  $password = "admin";
  $port = 1883;
  $topic = "obihai" . "/state_line" . "1"; // either one or 2
  }

$mqtt = new bluerhinos\phpMQTT($host, $port, "ClientID".rand());

if(!$mqtt->connect(true,NULL,$username,$password)){
  exit(1);
}

//currently subscribed topics
$topics[$topic] = array("qos"=>0, "function"=>"procmsg");
$mqtt->subscribe($topics,0);

while($mqtt->proc()){
}

$mqtt->close();
function procmsg($topic,$msg){
  echo "Msg Recieved: $msg";
}