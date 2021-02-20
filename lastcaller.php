<?php
require_once(__DIR__.'/config.php');
require_once(__DIR__.'/functions.php');
error_reporting(E_ALL); 
ini_set( 'display_errors','1');
$opencnam = 'https://api.opencnam.com/v2/phone/+15555555555';

if (isset($config['obihost'])) {
  $host = $config['obihost'];
  $username = $config['obiusername'];
  $password = $config['obipassword'];
} else {
$host = '192.168.42.2';
$username = "admin";
$password = "admin";
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