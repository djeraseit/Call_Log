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


function curl_post($Url,$username = 'admin', $password = 'admin',$payload = array()){

  $options = array(
    CURLOPT_URL            => $Url,
    CURLOPT_HEADER         => false,    
    CURLOPT_VERBOSE        => false,
    CURLOPT_POST        => true,
    CURLOPT_POSTFIELDS        => $payload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => 'CallBlocker',
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_NOPROXY => '*', // do not use proxy
    CURLOPT_SSL_VERIFYPEER => false,    // for https
    CURLOPT_USERPWD        => $username . ":" . $password,
    CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_REFERER => 'https://www.theodis.com',
    CURLOPT_TCP_KEEPALIVE => 1, // for callhistoryxml
    CURLOPT_TCP_KEEPIDLE => 2, // for call historyxml
);
 
 $ch = curl_init();
 curl_setopt_array( $ch, $options );
 $output = curl_exec($ch);

  // validate CURL status
  if(curl_errno($ch))
     throw new Exception(curl_error($ch), 500);
  // validate HTTP status code (user/password credential issues)
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($status_code != 200)
      throw new Exception("Response with Status Code [" . $status_code . "].", 500);

    if ($output === null)
     throw new Exception('No Response');

 if ($ch != null) curl_close($ch);

 return $output;
}

function curl_get($Url,$username = 'admin', $password = 'admin'){
 
  $options = array(
    CURLOPT_URL            => $Url,
    CURLOPT_HEADER         => false,    
    CURLOPT_VERBOSE        => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => 'CallBlocker',
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_NOPROXY => '*', // do not use proxy
    CURLOPT_SSL_VERIFYPEER => false,    // for https
    CURLOPT_USERPWD        => $username . ":" . $password,
    CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_REFERER => 'https://www.theodis.com',
    CURLOPT_TCP_KEEPALIVE => 1, // for callhistoryxml
    CURLOPT_TCP_KEEPIDLE => 2, // for call historyxml
);

 $ch = curl_init();
 curl_setopt_array( $ch, $options );
 $output = curl_exec($ch);

 //curl_setopt($ch, CURLOPT_PROXY, "proxy.YOURSITE.com");
 //curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
 //curl_setopt ($ch, CURLOPT_PROXYUSERPWD, "username:password"); 

  // validate CURL status
  if(curl_errno($ch))
     throw new Exception(curl_error($ch), 500);
  // validate HTTP status code (user/password credential issues)
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($status_code != 200)
      throw new Exception($Url . " OUTPUT: " . $output . " Response with Status Code [" . $status_code . "].", 500);

    if ($output === null)
     throw new Exception('No response');

 curl_close($ch);

 return $output;
}

function curl_errorhandler($errorno) {
    switch ($errorno) {
        case 401:
        $curlerror = "Invalid username and/or password";
        break;    
        default:
        $curlerror = null;
    }
return $curlerror;
}

function pb_alert($pbtoken = null,$payload = array()) {
  
    if (empty($payload)) {
    $payload = array('body'=>'Empty response','title'=>'Error','type'=>'note');
    $payload = json_encode($payload);
    }  
    $payload = json_encode($payload);

  $options = array(
    CURLOPT_URL            => 'https://api.pushbullet.com/v2/pushes',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HEADER         => false,
    CURLOPT_HTTPHEADER => array("Content-Type:application/json","Access-Token:{$pbtoken}"),    
    CURLOPT_VERBOSE        => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => 'CallBlocker',
    CURLOPT_FOLLOWLOCATION => true,
    //CURLOPT_NOPROXY => '*', // do not use proxy
    CURLOPT_SSL_VERIFYPEER => true,    // for https
    CURLOPT_TIMEOUT => 5,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_REFERER => 'https://www.theodis.com'
);

 $ch = curl_init();
 curl_setopt_array( $ch, $options );
 $output = curl_exec($ch);

  // validate CURL status
  if(curl_errno($ch))
     throw new Exception(curl_error($ch), 500);
  // validate HTTP status code (user/password credential issues)
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($status_code != 200)
      throw new Exception("Response with Status Code [" . $status_code . "].", 500);

 if ($ch != null) curl_close($ch);

 return $output;

}

function youmailLookup($apikey = null,$apisid = null,$phonenum = '',$payload = array()) {
  

  if (!empty($payload)) {
    $callerinfo['callee'] = $payload['callee'];
    $callerinfo['callerId'] = $payload['callerId'];
  } 
  
  //$callerinfo = [];
  if (empty($payload['callee'])) throw new Exception("Phone number missing!"); // throw new exception

  $data = http_build_query($callerinfo); 
  $url = "https://dataapi.youmail.com/api/v2/phone/{$phonenum}?" . $data;
  
  $options = array(
  CURLOPT_URL            => $url,
  CURLOPT_HEADER         => false,
  CURLOPT_HTTPHEADER => array("Accept:application/json","DataApiSid:{$apisid}","DataApiKey:{$apikey}"),    
  CURLOPT_VERBOSE        => false,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_USERAGENT => 'CallBlocker',
  CURLOPT_FOLLOWLOCATION => true,
  //CURLOPT_NOPROXY => '*', // do not use proxy
  CURLOPT_SSL_VERIFYPEER => true,    // for https
  CURLOPT_TIMEOUT => 5,
  CURLOPT_CONNECTTIMEOUT => 5,
  CURLOPT_REFERER => 'https://www.theodis.com'
);

//curl_setopt($ch, CURLOPT_PROXY, "proxy.YOURSITE.com");
//curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
//curl_setopt ($ch, CURLOPT_PROXYUSERPWD, "username:password"); 

$ch = curl_init();
curl_setopt_array( $ch, $options );

// need to catch error code 401 which is bad username and/or password
try {
  $output  = curl_exec( $ch );

  // validate CURL status
  if(curl_errno($ch))
      throw new Exception(curl_error($ch), 500);

  // validate HTTP status code (user/password credential issues)
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($status_code != 200)
      throw new Exception("URL: {$url} Response with Status Code [" . $status_code . "].", 500);

} catch(Exception $ex) {
    if ($ch != null) curl_close($ch);
    throw new Exception($ex);
}

if ($ch != null) curl_close($ch);

return $output;

}

// TODO: Possibly push onto array then sort out duplicates and/or create seperate update function

function addPhonebook ($phonenumber, $entry = array()) {
  if (!empty($entry)) {
    $phonebook = json_decode(file_get_contents('phone_book.json'), true);

    // TODO: Use foreach loop with array details to add to phonebook any keys and values

    foreach ($entry as $k => $v) {
      $phonebook[$phonenumber][$k] = $v;
    }
    /*
    if (isset($entry['Name']) || isset($entry['fullname'])) {
    $phonebook[$phonenumber]['Name'] = $entry['fullname'];
    }
    */

    //$phonebook[$phonenumber]['LastCall'] = $entry['lastcalldatetime'];
    
    file_put_contents('phone_book.json',json_encode($phonebook), LOCK_EX); // probably need not append since we read the entire phonebook into memory
    }
    return $entry;
}

function checkPhonebook($phonenumber,$fullname = null) {
  
    $phonebook = json_decode(file_get_contents('phone_book.json'), true);

    if (array_key_exists($phonenumber, $phonebook)) {
    $fullname = $phonebook[$phonenumber]['Name'];
    //$lastcalldatetime = $phonebook[$phonenumber]['LastCall'];
    $spamRisk = (isset($phonebook[$phonenumber]['spamRisk']) ? $phonebook[$phonenumber]['spamRisk'] : null);
    } elseif (!array_key_exists($phonenumber, $phonebook)) {
      $contact = array($phonenumber => array('Name'=>$fullname,'spamRisk'=>null));
    } else{
      $contact = array($phonenumber => array('Name'=>$fullname,'spamRisk'=>$spamRisk));
    } 
      $contact = array($phonenumber=> array('Name'=>$fullname,'spamRisk'=>$spamRisk));
    
    // TODO: Write back to phonebook possibly using array_merge (does not preserve keys/phonenumbers due to renumbering) / array_push
    
    //$phonebook = array_merge($phonebook,$contact);
    //$phonebook['phonenumber'][] = $contact;
    file_put_contents('phone_book.json',json_encode($phonebook), LOCK_EX); // probably need not append since we read the entire phonebook into memory
 
    return json_encode($contact);
}

function getCallerAndLookup($host, $username, $password, $state = "Ringing") {
  if ($state == "Ringing") {

    try {
      $currentcaller = getCurrentCaller();
      } catch (Exception $e) {
      echo $e-getMessage();
      }

      $callerinfo = json_decode($currentcaller,true);

      if (isset($callerinfo['Item'])) {
        $item = $callerinfo['Item'];
        //$hangupstatus = hangup($item);// hangup caller function
        $fullname = $callerinfo['Name'];
        $phonenumber = $callerinfo['Number'];
      } else { 
      echo "No current callers.";
      }

  } elseif ($state == "Off Hook") {
    // TODO: Check if on a call or just dialing a number (look for Connected)
  }
  return json_encode($callerinfo);
}

// TODO: Change this function to something else due to Item requirements
function getCallerAndHangup($scheme, $host, $username, $password) {

  // array of curl handles
$multiCurl = array();
// data to be returned
$result = array();
// multi handle
$mh = curl_multi_init();
$item = "0x45cbf0";
$prefix = "{$scheme}://{$host}/";

$url1 = $prefix . 'callstatus.htm';
$url2 = $prefix . 'callstatus.htm?item=' .$item;

$urls = array($url1,$url2);
$url_count = count($urls);

$authorization = $username . ":" . $password;

// Post fields for hanging up
$payload = array("item"=>$item,'value'=>'Remove');

$i = 0;
foreach($urls as $url) {
$multiCurl[$i] = curl_init();
curl_setopt($multiCurl[$i], CURLOPT_URL, $url);
curl_setopt($multiCurl[$i], CURLOPT_HEADER, 0);

if ($i == 1) {
curl_setopt($multiCurl[$i], CURLOPT_POST, true); // post on second url
curl_setopt($multiCurl[$i], CURLOPT_POSTFIELDS, $payload); // post on second url
}

curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, true);
curl_setopt($multiCurl[$i], CURLOPT_USERAGENT, 'CallBlocker');
curl_setopt($multiCurl[$i], CURLOPT_FOLLOWLOCATION, true);
curl_setopt($multiCurl[$i], CURLOPT_NOPROXY,'*'); // do not use proxy
curl_setopt($multiCurl[$i], CURLOPT_SSL_VERIFYPEER, false);    // for https
curl_setopt($multiCurl[$i], CURLOPT_USERPWD, $authorization);
curl_setopt($multiCurl[$i], CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
curl_setopt($multiCurl[$i], CURLOPT_TIMEOUT, 5);
curl_setopt($multiCurl[$i], CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($multiCurl[$i], CURLOPT_REFERER, 'https://www.theodis.com');
curl_multi_add_handle($mh, $multiCurl[$i]);
$i ++;
}

$active = null;
do {
  $mrc = curl_multi_exec($mh, $active);
  //usleep(100); // Maybe needed to limit CPU load (See P.S.)
  } while ($active);
  $content = array();

  $i = 0;
  foreach ($muliCurl AS $i => $c) {
  $content[$i] = curl_multi_getcontent($c);
  curl_multi_remove_handle($mh, $c);
  }

  if ($mh != null) curl_multi_close($mh);

  return $content;
  
}

function parseCurrentCaller($html) {

$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->validateOnParse = true;

$doc->loadHTML($html);

$doc->preserveWhiteSpace = false;

$activetable = $doc->getElementsByTagName('table')->item(0);

foreach($activetable->getElementsByTagName('tr') as $tractive) {
$tdactive = $tractive->getElementsByTagName('td');
$activecallfield = $tdactive->item(0)->nodeValue;
$totalactivecalls = (int) filter_var($activecallfield, FILTER_SANITIZE_NUMBER_INT);
}

$currentcall = [];
$call = [];

$calltable = $doc->getElementsByTagName('table')->item(1);

if ( $totalactivecalls >= 1 ){
$callremove = $doc->getElementsByTagName('form')->item(0)->getAttribute('action');
$formarray = explode("=",$callremove);
$callItem = $formarray[1];

// iterate over each row in the table
foreach($calltable->getElementsByTagName('tr') as $tr)
{
    $tds = $tr->getElementsByTagName('td'); // get the columns in this row
    if($tds->length >= 2 && !(empty($tds->item(1))))
    {
      $calltimes = $tds->item(0)->nodeValue; // possibly for start and end call times
      $callinfo = $tds->item(1)->nodeValue;
        if (!empty($callinfo)) $call[] = $callinfo; // B            
    }
}
// Future parse multiple calls (i.e. second table) if active calls >=1

// 0 - Call 1
// 1 - Terminal ID
// 2 - State
// 3 - Peer Name
// 4 - Peer Number
// 5 - Start Time
// 6 - Duration
// 7 - Direction
// 8 - Item for recording or hanging up


$callState = ucfirst($call[2]);
$callerName = $call[3];
$callerNumber = $call[4];
$callStart = $call[5];
$callDuration = $call[6];
$callDirection = $call[7];

$currentcall = array('State'=>$callState,'Name'=>$callerName,'Number'=>$callerNumber,'StartTime'=>$callStart,'Duration'=>$callDuration,
'Direction'=>$callDirection, 'Item'=>$callItem,'ActiveCalls'=> $totalactivecalls);
} //end if statement

return json_encode($currentcall);
}

function socket_connect_timeout($host, $port, $timeout=100){

  $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

  /**
   * Set the send and receive timeouts super low so that socket_connect
   * will return to us quickly. We then loop and check the real timeout
   * and check the socket error to decide if its conected yet or not.
   */
  $connect_timeval = array(
      "sec"=>0,
      "usec" => 100
  );
  socket_set_option(
      $socket,
      SOL_SOCKET,
      SO_SNDTIMEO,
      $connect_timeval
  );
  socket_set_option(
      $socket,
      SOL_SOCKET,
      SO_RCVTIMEO,
      $connect_timeval
  );

  $now = microtime(true);

  /**
   * Loop calling socket_connect. As long as the error is 115 (in progress)
   * or 114 (already called) and our timeout has not been reached, keep
   * trying.
   */
  $err = null;
  $socket_connected = false;
  do{
      socket_clear_error($socket);
      $socket_connected = @socket_connect($socket, $host, $port);
      $err = socket_last_error($socket);
      $elapsed = (microtime(true) - $now) * 1000;
  }
  while (($err === 115 || $err === 114) && $elapsed < $timeout);

  /**
   * For some reason, socket_connect can return true even when it is
   * not connected. Make sure it returned true the last error is zero
   */
  $socket_connected = $socket_connected && $err === 0;

  if($socket_connected){

      /**
       * Set keep alive on so the other side does not drop us
       */
      socket_set_option($socket, SOL_SOCKET, SO_KEEPALIVE, 1);

      /**
       * set the real send/receive timeouts here now that we are connected
       */
      $timeval = array(
          "sec" => 0,
          "usec" => 0
      );
      if($timeout >= 1000){
          $ts_seconds = $timeout / 1000;
          $timeval["sec"] = floor($ts_seconds);
          $timeval["usec"] = ($ts_seconds - $timeval["sec"]) * 1000000;
      } else {
          $timeval["usec"] = $timeout * 1000;
      }
      socket_set_option(
          $socket,
          SOL_SOCKET,
          SO_SNDTIMEO,
          $timeval
      );
      socket_set_option(
          $socket,
          SOL_SOCKET,
          SO_RCVTIMEO,
          $timeval
      );

  } else {

      $elapsed = round($elapsed, 4);

      if(!is_null($err) && $err !== 0 && $err !== 114 && $err !== 115){
          $message = "Failed to connect to $host:$port. ($err: ".socket_strerror($err)."; after {$elapsed}ms)";
      } else {
          $message = "Failed to connect to $host:$port. (timed out after {$elapsed}ms)";
      }

      throw new Exception($message);

  }

  return $socket;

}

function testObihaiConnectivity() {
 /* Get the port for the WWW service. */
$service_port = 80;

/* Get the IP address for the target host. */
$address = "192.168.42.3";

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

$in = "GET / HTTP/1.1\r\n";
$in .= "Host: {$address}\r\n";
$in .= "Connection: Close\r\n\r\n";
$out = '';

socket_write($socket, $in, strlen($in));

echo "Reading response:\n\n";
while ($out = socket_read($socket, 512)) {
    echo $out;
}

socket_close($socket);

if(!empty($out)) {
  $status = "online";
} else {
  $status = "offline";
}
return $status;
}

function userDigitMapsJson($xmldata) {
  libxml_use_internal_errors(true);
  $xmlDoc = new DOMDocument();
  $xmlDoc->preserveWhiteSpace = false;
  $xmlDoc->validateOnParse = true;
  $xmlDoc->encoding = "utf-8";
  $xmlDoc->loadXML($xmldata);
  //print $xmlDoc->saveXML();
  //die();
  //$i = $xmlDoc->documentElement;
  $fields = $xmlDoc->getElementsByTagName('parameter');

  foreach ($fields as $field){
   // var_dump($field);
   // die();
    $childNodes = $field->childNodes;
   // var_dump($childNodes);
    foreach ($childNodes as $child){
      echo $child->nodeName;
      echo $child->nodeValue;
     // echo getNextSibling($child);
    }
    //var_dump($field);
  //die();
    //$label = $field->getElementsByTagName('object')->item(0)->nodeValue;
    //$label = $field->getElementsByTagName('parameter')->nodeValue;
    //echo $field->getElementsByTagName('syntax')->item(0)->nodeValue;
    //echo $label;
  }
  //foreach ($i->childNodes as $item){
  //  print $item->nodeName . " = " . $item->nodeValue . PHP_EOL;
  //}
  /*
  die();
  $json = json_encode($xml);
  print_r($json);
  $array = json_decode($json,TRUE);
//return $array;
var_dump($array);
die();

  foreach ($array as $v => $digitmap) {
  
    foreach ($digitmap as $map){
     
     $historyname= $map['Terminal'][0]['Peer']['@attributes']['name'];
     $historynumber = $map['Terminal'][0]['Peer']['@attributes']['number'];
     $historydirection = $map['Terminal'][0]['@attributes']['dir'];
     $historydate = $map['@attributes']['date'];
     $historytime = $map['@attributes']['time'];
     $historyevents = $map['Terminal'][0]['Event']; // array
     // $callhistoryjson[] = $history;
     $digitmapjson[] = ["Name"=> $historyname,"Number"=> $historynumber, "Direction"=> $historydirection,
                        "Date"=> $historydate,"Time" => $historytime, "Events" => $historyevents];
    }
  }

  return json_encode($digitmapjson);
*/
}

function callHistoryJson($xmldata) {
  $xml = simplexml_load_string($xmldata, "SimpleXMLElement", LIBXML_NOCDATA);
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);

  foreach ($array as $v => $call) {
  
    foreach ($call as $caller){
     
     $historyname= $caller['Terminal'][0]['Peer']['@attributes']['name'];
     $historynumber = $caller['Terminal'][0]['Peer']['@attributes']['number'];
     $historydirection = $caller['Terminal'][0]['@attributes']['dir'];
     $historydate = $caller['@attributes']['date'];
     $historytime = $caller['@attributes']['time'];
     $historyevents = $caller['Terminal'][0]['Event']; // array
     // $callhistoryjson[] = $history;
     $callhistoryjson[] = ["Name"=> $historyname,"Number"=> $historynumber, "Direction"=> $historydirection,
                        "Date"=> $historydate,"Time" => $historytime, "Events" => $historyevents];
    }
  }

  return json_encode($callhistoryjson);
}

function openCNAM ($number) {
  $endpoint = 'https://api.opencnam.com/v2/phone/+' . $number;
}

function updateRisk ($number, $data) {
    $phonebook = json_decode(file_get_contents('phone_book.json'), true);
 
    $data = json_decode($data,true);
  
    if (array_key_exists($number,$phonebook)) {
    $phonebook[$number]['spamRisk'] = $data['spamRisk']['level'];
    // $phonebook[$number][] = array('spamRisk' => $data['spamRisk']['level']);
    }
  $phonebook = json_encode($phonebook);
  $updatedPhonebook = file_put_contents('phone_book.json',$phonebook);
  return $updatedPhonebook;
}

function reboot($obihai){
$config = require __DIR__.'/config.php';

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

return json_encode("Rebooting");
}

function addBlacklistName ($name) {
  $blacklist = json_decode(file_get_contents('blacklist_names.json'), true);

  if (!array_key_exists($name,$blacklist)) {
  $blacklist[] = $name;
 }
$blacklist = json_encode($blacklist);
$updatedBlacklist = file_put_contents('blacklist_names.json',$blacklist);
return $updatedBlacklist;
}

function addBlacklistNumber ($number) {
  $blacklist = json_decode(file_get_contents('blacklist_numbers.json'), true);

  if (!array_key_exists($number,$blacklist)) {
  $blacklist[] = $number;
 }
$blacklist = json_encode($blacklist);
$updatedBlacklist = file_put_contents('blacklist_numbers.json',$blacklist);
return $updatedBlacklist;
}

function addWhitelistName ($name) {
  $whitelist = json_decode(file_get_contents('whitelist_names.json'), true);

  if (!array_key_exists($name,$whitelist)) {
  $whitelist[] = $name;
 }
$whitelist = json_encode($whitelist);
$updatedWhitelist = file_put_contents('whitelist_names.json',$whitelist);
return $updatedWhitelist;
}

function addWhitelistNumber ($number) {
  $whitelist = json_decode(file_get_contents('whitelist_numbers.json'), true);

  if (!array_key_exists($number,$whitelist)) {
  $whitelist[] = $number;
 }
$blacklist = json_encode($whitelist);
$updatedWhitelist = file_put_contents('whitelist_numbers.json',$whitelist);
return $updatedWhitelist;
}

function generateBlackListNumbers($phonebookjson){
  $blacklist = json_decode($phonebookjson,true);
}

function generateWhiteListNumbers($phonebookjson){
  $blacklist = json_decode($phonebookjson,true);
}

function numverify($accessKey,$phoneNumber = '15555555555') {

$ch = curl_init('http://apilayer.net/api/validate?access_key='.$accessKey.'&number='.$phoneNumber.'&country_code='.'&format=1');  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
$json = curl_exec($ch);
curl_close($ch);

// Decode JSON response:
$validationResult = json_decode($json, true);

// Access and use your preferred validation result objects
$validationResult['valid'];
$validationResult['country_code'];
$validationResult['carrier'];
$validationResult['number']; 
$validationResult['local_format']; 
$validationResult['international_format']; 
$validationResult['country_prefix'];    
$validationResult['country_code'];    
$validationResult['country_name'];
$validationResult['location'];         
$validationResult['line_type'];          

return($validationResult);
   // & country_code = default blank
   // & format = 1

}

function getObihaiCredentials() {
  $config = require __DIR__.'/config.php';

if (isset($config['obihai']['credentials']['host'])) {
   $host = $config['obihai']['credentials']['host'];
   $username = $config['obihai']['credentials']['username'];
   $password = $config['obihai']['credentials']['password'];
   $scheme = $config['obihai']['credentials']['scheme'];
 } else {
      die('Software must be configured.');
}
return $config['obihai']['credentials'];
}

function twilioEkataInstall(){
  $authorization = $sid . ":" . $authToken;
  $addOns = array('AvailableAddOnSid'=>'XB65bee4ad5be08e28368def1be1a3efee',
  'AcceptTermsOfService'=>'true','Configuration'=>'{}');
  $url ='https://preview.twilio.com/marketplace/InstalledAddOns';

  //-u $TWILIO_ACCOUNT_SID:$TWILIO_AUTH_TOKEN
$options = array(
  CURLOPT_URL            => $url,
  CURLOPT_HEADER         => false,
  CURLOPT_POST  => true,
  CURLOPT_POSTFIELDS => $addOns,    
  CURLOPT_VERBOSE        => false,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_USERAGENT => 'CallBlocker',
  CURLOPT_FOLLOWLOCATION => true,
  //CURLOPT_NOPROXY => '*', // do not use proxy
  CURLOPT_SSL_VERIFYPEER => true,    // for https
  CURLOPT_TIMEOUT => 5,
  CURLOPT_CONNECTTIMEOUT => 5,
  CURLOPT_USERPWD => $authorization,
  CURLOPT_REFERER => 'https://www.theodis.com',
  CURLOPT_HTTPAUTH => CURLAUTH_BASIC
);

$ch = curl_init();
curl_setopt_array( $ch, $options );
$output = curl_exec($ch);

 //curl_setopt($ch, CURLOPT_PROXY, "proxy.YOURSITE.com");
 //curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
 //curl_setopt ($ch, CURLOPT_PROXYUSERPWD, "username:password"); 

  // validate CURL status
  if(curl_errno($ch))
     throw new Exception(curl_error($ch), 500);
  // validate HTTP status code (user/password credential issues)
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($status_code != 200)
      throw new Exception("Response with Status Code [" . $status_code . "]." . $output, 500);

 curl_close($ch);

 return $output;

}
function twilioNomorobo($sid, $authToken, $callee = '+17136331642', $phonenumber){
  $authorization = $sid . ":" . $authToken;
  $addOns = array('AddOns'=>'nomorobo_spamscore','AddOns.nomorobo_spamscore.secondary_address'=>$callee);
  $phonenumber = "+1" . $phonenumber; // optional country code -- defaults to US
  
  $url = "https://lookups.twilio.com/v1/PhoneNumbers/{$phonenumber}/?AddOns=nomorobo_spamscore&AddOns.nomorobo_spamscore.secondary_address={$callee}";
  $options = array(
    CURLOPT_URL            => $url,
    CURLOPT_HEADER         => false,    
    CURLOPT_VERBOSE        => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => 'CallBlocker',
    CURLOPT_FOLLOWLOCATION => true,
    //CURLOPT_NOPROXY => '*', // do not use proxy
    CURLOPT_SSL_VERIFYPEER => true,    // for https
    CURLOPT_TIMEOUT => 5,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_USERPWD => $authorization,
    CURLOPT_REFERER => 'https://www.theodis.com',
    CURLOPT_HTTPAUTH => CURLAUTH_BASIC
);

 $ch = curl_init();
 curl_setopt_array( $ch, $options );
 $output = curl_exec($ch);

 //curl_setopt($ch, CURLOPT_PROXY, "proxy.YOURSITE.com");
 //curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
 //curl_setopt ($ch, CURLOPT_PROXYUSERPWD, "username:password"); 

  // validate CURL status
  if(curl_errno($ch))
     throw new Exception(curl_error($ch), 500);
  // validate HTTP status code (user/password credential issues)
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($status_code != 200)
      throw new Exception("Response with Status Code [" . $status_code . "]." . $output, 500);



 curl_close($ch);

 return $output;
}

function hangup($item ='0x45cbf0') {
  $config = require __DIR__.'/config.php';

if (isset($config['obihai']['host'])) {
  $host = $config['obihai']['host'];
  $username = $config['obihai']['credentials']['username'];
  $password = $config['obihai']['credentials']['password'];
  $scheme = $config['obihai']['scheme'];
} else {
die('Configuration needed.');
}

$value = "Remove";
$payload = array("item"=>$item,'value'=>$value);
$pagename = 'callstatus.htm?item=' . $item;
$scheme = "http";

$url = "{$scheme}://{$host}/{$pagename}";
$raw_response = 'Error';

try {
  $raw_response  =  curl_post($url,$username,$password,$payload);
}
 catch(Exception $ex) {
    echo $ex->getMessage() .$url . ' ' . $username . '' . $password;
    print_r($payload);
    $raw_response = "we caught an exception.";
}

return $raw_response;
}

function parseSystemStatus($xml){

$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->validateOnParse = true;
$doc->loadXML($xml);
$doc->preserveWhiteSpace = false;

$activetable = $doc->getElementsByTagName('table')->item(0);

foreach($activetable->getElementsByTagName('tr') as $tractive) {
$tdactive = $tractive->getElementsByTagName('td');
$activecallfield = $tdactive->item(0)->nodeValue;

}

$currentcall = [];
$call = [];

$calltable = $doc->getElementsByTagName('table')->item(1);

if ( $totalactivecalls >= 1 ){
$callremove = $doc->getElementsByTagName('form')->item(0)->getAttribute('action');
$formarray = explode("=",$callremove);
$callItem = $formarray[1];

// iterate over each row in the table
foreach($calltable->getElementsByTagName('tr') as $tr)
{
    $tds = $tr->getElementsByTagName('td'); // get the columns in this row
    if($tds->length >= 2 && !(empty($tds->item(1))))
    {
      $calltimes = $tds->item(0)->nodeValue; // possibly for start and end call times
      $callinfo = $tds->item(1)->nodeValue;
        if (!empty($callinfo)) $call[] = $callinfo; // B            
    }
}
// Future parse multiple calls (i.e. second table) if active calls >=1

// 0 - Call 1
// 1 - Terminal ID
// 2 - State
// 3 - Peer Name
// 4 - Peer Number
// 5 - Start Time
// 6 - Duration
// 7 - Direction
// 8 - Item for recording or hanging up


$callState = ucfirst($call[2]);
$callerName = $call[3];
$callerNumber = $call[4];
$callStart = $call[5];
$callDuration = $call[6];
$callDirection = $call[7];

$currentcall = array('State'=>$callState,'Name'=>$callerName,'Number'=>$callerNumber,'StartTime'=>$callStart,'Duration'=>$callDuration,
'Direction'=>$callDirection, 'Item'=>$callItem,'ActiveCalls'=> $totalactivecalls);
} //end if statement

return json_encode($currentcall);

}

function getCurrentCaller($obihai = null) {
  $config = require __DIR__.'/config.php';

$pagename = 'callstatus.htm';

if (isset($config['obihai']['host'])) {
  $host = $config['obihai']['host'];
  $username = $config['obihai']['credentials']['username'];
  $password = $config['obihai']['credentials']['password'];
  $scheme = $config['obihai']['scheme'];
} else {
  throw new Exception("Software must be configured first.");
}

$url = "{$scheme}://{$host}/{$pagename}";

try {
    $raw_response  =  curl_get($url,$username, $password);
    }

 catch(Exception $ex) {
    $results = $ex->getMessage() . "We caught an exception.";
    $raw_response = null;
    }

  // Testing with flat files
  //  $raw_response = file_get_contents('callstatus-example2.txt');

  if (!empty($raw_response)) {
    $results = parseCurrentCaller($raw_response);
  }
  return $results;
}

function cloudflareKV($kvinfo = array(),$action = "verify",$kvdetails = null){
  $config = require __DIR__.'/config.php';

  if (isset($config['cloudflare']['accountid'])) {
    $accountid = $config['cloudflare']['accountid'];
    $namespaceid = $config['cloudflare']['namespaceid'];
    $authkey = $config['cloudflare']['authkey'];
  
  } else {
    die('Cloudflare not configured.');
  }

  $options = array(
    CURLOPT_HEADER         => false,    
    CURLOPT_VERBOSE        => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => 'CallBlocker',
    CURLOPT_FOLLOWLOCATION => true,
    //CURLOPT_NOPROXY => '*', // do not use proxy
    CURLOPT_SSL_VERIFYPEER => true,    // for https
    CURLOPT_TIMEOUT => 5,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_REFERER => 'https://www.theodis.com'
);
  switch ($action){
    case "verify":
      // build variables for verification
      $headers = array("Authorization: Bearer {$authkey}","Content-Type: application/json");
      $curlOptions = array('CURLOPT_URL'=>"https://api.cloudflare.com/client/v4/user/tokens/verify");
      $options = array_merge($options, $curlOptions);
      $method = "GET";
    break;
    case "read":
      //build variables for curl
      $headers = array("Authorization: Bearer {$authkey}");
      $curlOptions = array('CURLOPT_URL'=>"https://api.cloudflare.com/client/v4/accounts/{$accountid}/storage/kv/namespaces/{$namespaceid}/values/{$key}");
      $options = array_merge($options, $curlOptions);
      $method = "GET";
    break;
    case "create-single":
      //build variables for curl
      $headers = array("Authorization: Bearer {$authkey}","Content-Type: text/plain");
      $curlOptions = array('CURLOPT_POSTFIELDS'=>$data,'CURLOPT_CUSTOMREQUEST'=>"PUT","CURLOPT_URL"=>"https://api.cloudflare.com/client/v4/accounts/{$accountid}/storage/kv/namespaces/{$namespaceid}/values/{$key}");
      $options = array_merge($options, $curlOptions);
      $method = "PUT";
    break;
    case "delete":
      //build variables for curl
      $headers = array("Authorization: Bearer {$authkey}");
      $curlOptions = array('CURLOPT_CUSTOMREQUEST'=>'DELETE','CURLOPT_URL'=>"https://api.cloudflare.com/client/v4/accounts/{$accountid}/storage/kv/namespaces/{$namespaceid}/values/{$key}");
      $options = array_merge($options, $curlOptions);
      $method = "DELETE";
    break;
    default:
      // verification
      $headers = array("Authorization: Bearer {$authkey}","Content-Type: application/json");
      $curlOptions = array('CURLOPT_URL'=>"https://api.cloudflare.com/client/v4/user/tokens/verify");
      $options = array_merge($options, $curlOptions);
      $method = "GET";
  }
  //curl_setopt($ch, CURLOPT_PROXY, "proxy.YOURSITE.com");
//curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
//curl_setopt ($ch, CURLOPT_PROXYUSERPWD, "username:password"); 

$ch = curl_init();
curl_setopt_array( $ch, $options );

// need to catch error code 401 which is bad username and/or password
try {
  $output  = curl_exec( $ch );

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

return $output;

}