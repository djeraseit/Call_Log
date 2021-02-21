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


//require_once(__DIR__.'/config.php');
$config = require __DIR__.'/../config.php';
require_once(__DIR__.'/functions.php');

$pagename = 'callstatus.htm';
$scheme = 'http';

if (isset($config['obihost'])) {
    $host = $config['obihost'];
    $username = $config['obiusername'];
    $password = $config['obipassword'];
  } else {
  $host = '192.168.42.2';
  $username = "admin";
  $password = "admin";
  }

$url = "{$scheme}://{$host}/{$pagename}";

try {
  $raw_response  =  curl_get($url,$username = 'admin', $password = 'megalith');
/*
  // validate CURL status
  if(curl_errno($ch))
     throw new Exception(curl_error($ch), 500);
  // validate HTTP status code (user/password credential issues)
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($status_code != 200)
      throw new Exception("Response with Status Code [" . $status_code . "].", 500);
*/
    }

 catch(Exception $ex) {
 /*
    if ($raw_response === null) echo('No response');
     throw new Exception($ex);
*/
    }

//echo ($raw_response);
$raw_response = file_get_contents('callstatus-example2.txt');

$results = parseCurrentCaller($raw_response);