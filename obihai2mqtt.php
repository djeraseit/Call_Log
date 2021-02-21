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


//php obihai2mqtt.php --obihai_host 192.168.42.2 --obihai_user admin --obihai_pass megalith --mqtt_host eagle.rmq.cloudamqp.com --mqtt_user rcndzvbo:rcndzvbo --mqtt_topic obihai --mqtt_pass E-BaZornNc1uuSID1kjuVmzDsj1UrxVY

require __DIR__ . '/phpMQTT.php';

$longOpts = array(
  'poll_freq:',
  'obihai_host:',
  'obihai_user:',
  'obihai_pass:',
  'mqtt_host:',
  'mqtt_port:',
  'mqtt_user:',
  'mqtt_pass:',
  'mqtt_topic:',
);

$options = getopt(null, $longOpts);

if (!isset($options['obihai_host']))
{
  print "Parameter --obihai_host is mandatory!\n";
  exit(1);
}

$poll_freq = (isset($options['poll_freq']) ? $options['poll_freq'] : 5);

$obihai_host = $options['obihai_host'];
$obihai_user = (isset($options['obihai_user']) ? $options['obihai_user'] : 'admin');
$obihai_pass = (isset($options['obihai_pass']) ? $options['obihai_pass'] : 'admin');

$mqtt_host = (isset($options['mqtt_host']) ? $options['mqtt_host'] : 'localhost');
$mqtt_port = (isset($options['mqtt_port']) ? $options['mqtt_port'] : 1883);
$mqtt_user = (isset($options['mqtt_user']) ? $options['mqtt_user'] : '');
$mqtt_pass = (isset($options['mqtt_pass']) ? $options['mqtt_pass'] : '');
$mqtt_topic = (isset($options['mqtt_topic']) ? $options['mqtt_topic'] : 'obihai');

//$mqtt = new phpMQTT($mqtt_host, $mqtt_port, 'obihai2mqtt_'.rand());
$mqtt = new bluerhinos\phpMQTT($mqtt_host, $mqtt_port, 'obihai2mqtt_'.rand());

// TODO: Check if this is best place to put curl connection. MQTT seems to work normally.

//$url = "http://" . $obihai_host . "/PI_FXS_1_Stats.xml" . "&time=".time(); //add time to prevent cache
$url = "http://" . $obihai_host . "/PI_FXS_1_Stats.xml"; //add time to prevent cache

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
curl_setopt($curl, CURLOPT_NOPROXY, "*"); // no proxy
curl_setopt($curl, CURLOPT_USERPWD, "$obihai_user:$obihai_pass");

$output = curl_exec($curl);

/*
$config = array(
  'indent' => true,
  'clean' => true,
  'input-xml'  => true,
  'output-xml' => true,
  'wrap'       => false
  );

$tidy = new Tidy();
$xml = $tidy->repairfile($badXML, $config);
echo $xml;
*/
//$searchPage = mb_convert_encoding($htmlUTF8Page, 'HTML-ENTITIES', "UTF-8"); 
while (true)
{
  $states = array();
  $xml = new SimpleXMLElement($output, LIBXML_NOERROR |  LIBXML_ERR_NONE);
 // $xml = simplexml_load_file($output, "SimpleXMLElement", LIBXML_NOERROR |  LIBXML_ERR_NONE); //ignore errors
  $parameters = $xml->xpath('//parameter');
  foreach ($parameters as $parameter)
  {
    foreach ($parameter->attributes() as $attribute => $value)
    {
      if ($attribute == 'name' && $value == 'State')
      {
        $states[] = $parameter->value['current'];
      }
    }
  }
  
  if ($mqtt->connect(true, null, $mqtt_user, $mqtt_pass))
  {
    $i = 1;
    foreach ($states as $state)
    {
      $mqtt->publish($mqtt_topic . '/state_line' . $i, $state);
      echo $state . PHP_EOL; // On Hook, Ringing, Off Hook
      echo $mqtt_topic . '/state_line' . $i . PHP_EOL;
      // need to do something like call block when Ringing, and when Off Hook start recording, when On Hook, get last caller, etc
      $i++;
    }    
    $mqtt->close();
  }
  else
  {
    print "Cannot connect to MQTT.\n";
  }
    
  sleep($poll_freq);
}