<?php
error_reporting(E_ALL); 
ini_set( 'display_errors','1');
require_once(__DIR__.'/config.php');
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

echo ($raw_response);
