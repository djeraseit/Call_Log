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
 die('Software must be configured.');
 }
 

$pagename = 'callhistory.htm';


$opencnam = 'https://api.opencnam.com/v2/phone/+155555555';

$item = null;
$pagenum = 0;



$ch = curl_init();

curl_setopt_array( $ch, $options );
for ($pagenum = 0; $pagenum <=3; $pagenum) {
    
    $url = "{$scheme}://{$host}/{$pagename}?{$pagenum}";


    $options = array(
        CURLOPT_URL            => $url,
        CURLOPT_HEADER         => false,    
        CURLOPT_VERBOSE        => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_NOPROXY => '*', // do not use proxy
        CURLOPT_SSL_VERIFYPEER => false,    // for https
        CURLOPT_USERPWD        => $username . ":" . $password,
        CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST
);
    
    echo "The page number is $pagenum" . PHP_EOL;

// need to catch error code 401 which is bad username and/or password
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

echo $raw_response;
} 

