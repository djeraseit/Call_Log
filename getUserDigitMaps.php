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


$connect = getObihaiCredentials();

$pagename = 'DIGITMAPS_.xml';

$url = "{$connect['scheme']}://{$connect['host']}/{$pagename}";

$options = array(
        CURLOPT_URL            => $url,
        CURLOPT_HEADER         => false,    
        CURLOPT_VERBOSE        => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_IGNORE_CONTENT_LENGTH => true,
        CURLOPT_TCP_KEEPALIVE => 1,
        CURLOPT_TCP_KEEPIDLE => 2,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,    // for https
        CURLOPT_USERPWD        => $connect['username'] . ":" . $connect['password'],
        CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
        CURLOPT_POST           => true,
        CURLOPT_FAILONERROR => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_NOPROXY => '*', // do not use proxy
);


$ch = curl_init();

curl_setopt_array( $ch, $options );

$raw_response  = curl_exec( $ch );

  //print_r($raw_response);

  // validate CURL status
    if(curl_errno($ch))
     throw new Exception(curl_error($ch), 500);
  // validate HTTP status code (user/password credential issues)
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($status_code != 200)
      throw new Exception("Response with Status Code [" . $status_code . "].", 500);

    if ($ch != null) curl_close($ch);

   
    echo userDigitMapsJson($raw_response);
    
//$jsonresponse = callHistoryJson($raw_response);
//file_put_contents('user_digit_maps.json',$jsonresponse, LOCK_EX); //append?