<?php

/**
* Megalith Technologies
*
* @category VoIP
* @author Theodis Butler
* @copyright Copyright (c) 2021 Megalith Technologies (https://megalithtechnologies.com)
* @license https://www.gnu.org/licenses/gpl-3.0.en.html
*/

require_once(__DIR__.'/config.php');
require_once(__DIR__.'/functions.php');

// return config.php (using file_get_contents or $config)
if (isset($config['obihost'])) {
  $host = $config['obihost'];
  $username = $config['obiusername'];
  $password = $config['obipassword'];
  $scheme = $config['scheme'];
} else {
$host = '192.168.42.2';
$username = "admin";
$password = "admin";
$scheme = $config['scheme'];
}

if (isset($GET['item'])) {
$item = $_GET['item'];
} else {
  $item = "0x45cbf0";
}
$value = "Remove";
$payload = array("item"=>$item,'value'=>$value);
$pagename = 'callstatus.htm?item=' . $item;
$scheme = "http";

$url = "{$scheme}://{$host}/{$pagename}";

try {
  $raw_response  =  curl_post($url,$username,$password,$payload);
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