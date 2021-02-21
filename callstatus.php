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
/*
$pagename = 'callstatus.htm';

if (isset($config['obihai']['host'])) {
  $host = $config['obihai']['host'];
  $username = $config['obihai']['credentials']['username'];
  $password = $config['obihai']['credentials']['password'];
  $scheme = $config['obihai']['scheme'];
} else {
  die('Please configure the software.');
}

$url = "{$scheme}://{$host}/{$pagename}";

try {
    $raw_response  =  curl_get($url,$username, $password);
    }

 catch(Exception $ex) {
    echo $ex->getMessage() . "We caught an exception.";
    $raw_response = null;
    }

  // Testing with flat files
  //  $raw_response = file_get_contents('callstatus-example2.txt');

  if (!empty($raw_response)) {
$results = parseCurrentCaller($raw_response);

var_dump($results);
  }
  */
  print_r(getCurrentCaller());