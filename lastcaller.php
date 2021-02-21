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

$config = require __DIR__.'/config.php';
require_once(__DIR__.'/functions.php');



if (isset($config['obihai']['host'])) {
  $host = $config['obihai']['host'];
  $username = $config['obihai']['credentials']['username'];
  $password = $config['obihai']['credentials']['password'];
  $scheme = $config['obihai']['scheme'];
} else {
$host = '192.168.42.2';
$username = "admin";
$password = "admin";
$scheme = "http";
}

$pagename = 'PI_FXS_1_Stats.xml';
$scheme = 'http';
$url = "{$scheme}://{$host}/{$pagename}";

$options = array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,    // for https
        CURLOPT_USERPWD        => $username . ":" . $password,
        CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
        CURLOPT_NOPROXY => '*', // do not use proxy
);
$ch = curl_init();
curl_setopt_array( $ch, $options );
try {
  $raw_response  = curl_exec( $ch );
  // validate CURL status
  if(curl_errno($ch))
      throw new Exception(curl_error($ch), 500);
  // validate HTTP status code (user/password credential issues)
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($status_code != 200)
      throw new Exception("Response with Status Code [" . $status_code . "].", 500);
} catch(Exception $ex) {
    if ($ch != null) curl_close($ch);
    throw new Exception($ex);
}
if ($ch != null) curl_close($ch);
  //$states = [];
  
  $xml = new SimpleXMLElement($raw_response);

  $parameters = $xml->xpath('//parameter');
  foreach ($parameters as $parameter)
  {
    foreach ($parameter->attributes() as $attribute => $value)
    {
      if ($attribute == 'name' && $value == 'LastCallerInfo')
      {
        $states[] = $parameter->value['current'];
        // inside loop so prints twice
        //print_r(current($states[0]));
$lastcaller = current($states[0]);
$lastcallerInfo = explode("' ",$lastcaller);
$lastcallerName = trim($lastcallerInfo[0],"'");
$lastcallerPhone = trim($lastcallerInfo[1]);

    }
    }
  }
//echo 'Phone: ' . $lastcallerPhone . PHP_EOL;
//echo 'Name: ' . $lastcallerName  . PHP_EOL;  
$payload = array('title'=>'Last Caller','body'=>$lastcaller,'type'=>'note');
echo pb_alert('o.mjCLA2hY2n5jVnwGwHrIDO76KccJtIbl',$payload);