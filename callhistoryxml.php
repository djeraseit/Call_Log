<?php

require_once(__DIR__.'/config.php');
require_once(__DIR__.'/functions.php');


if (isset($config['obihost'])) {
   $host = $config['obihost'];
   $username = $config['obiusername'];
   $password = $config['obipassword'];
 } else {
 $host = '192.168.42.2';
 $username = "admin";
 $password = "admin";
 }

$pagename = 'callhistory.xml';
$scheme = 'http';
$url = "{$scheme}://{$host}/{$pagename}";


$options = array(
        CURLOPT_URL            => $url,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_IGNORE_CONTENT_LENGTH=> true,
        CURLOPT_TCP_KEEPALIVE => 1,
        CURLOPT_TCP_KEEPIDLE => 2,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,    // for https
        CURLOPT_USERPWD        => $username . ":" . $password,
        CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
        CURLOPT_POST           => true,
        CURLOPT_FAILONERROR => true
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
}

 catch(Exception $ex) {
    if ($ch != null) curl_close($ch);
     throw new Exception($ex);
}

echo ($raw_response);

