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

$pagename = 'rebootgetconfig.htm';

$url = "{$scheme}://{$host}/{$pagename}";

$options = array(
        CURLOPT_URL            => $url,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_IGNORE_CONTENT_LENGTH=> true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,    // for https
        CURLOPT_USERPWD        => $username . ":" . $password,
        CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST
);

$ch = curl_init();

curl_setopt_array( $ch, $options );

try {
  $raw_response  = curl_exec( $ch );

  if(curl_errno($ch))
     throw new Exception(curl_error($ch), 500);
     
  // validate HTTP status code (user/password credential issues)
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($status_code != 200)
      throw new Exception("Response with Status Code [" . $status_code . "].", 500);
}

 catch(Exception $ex) {
    if ($ch != null) curl_close($ch);
     throw new Exception($ex);
}

echo "Rebooting";
