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
 // OK cool - then let's create a new cURL resource handle
 $ch = curl_init();
 curl_setopt_array( $ch, $options );

 // Download the given URL, and return output
 $output = curl_exec($ch);

 // Close the cURL resource, and free system resources
 curl_close($ch);

 return $output;
}

function curl_get($Url,$username = 'admin', $password = 'admin'){
 

  // Now set some options (most are optional)
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
 // OK cool - then let's create a new cURL resource handle
 $ch = curl_init();
 curl_setopt_array( $ch, $options );

 //curl_setopt($ch, CURLOPT_PROXY, "proxy.YOURSITE.com");
 //curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
 //curl_setopt ($ch, CURLOPT_PROXYUSERPWD, "username:password"); 

 // Download the given URL, and return output
 $output = curl_exec($ch);

 // Close the cURL resource, and free system resources
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

function pb_alert($pbtoken = 'o.mjCLA2hY2n5jVnwGwHrIDO76KccJtIbl',$payload = array()) {
  
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
    CURLOPT_SSL_VERIFYPEER => false,    // for https
    CURLOPT_TIMEOUT => 5,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_REFERER => 'https://www.theodis.com'
);
 // OK cool - then let's create a new cURL resource handle
 $ch = curl_init();
 curl_setopt_array( $ch, $options );

 //curl_setopt($ch, CURLOPT_PROXY, "proxy.YOURSITE.com");
 //curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
 //curl_setopt ($ch, CURLOPT_PROXYUSERPWD, "username:password"); 

 // Download the given URL, and return output
 $output = curl_exec($ch);

 // Close the cURL resource, and free system resources
 curl_close($ch);
 return $output;

}

function youmailLookup($apikey = 'o.mjCLA2hY2n5jVnwGwHrIDO76KccJtIbl',$apisid = '',$phone = '',$payload = array()) {
  
  if (empty($payload)) {
  $payload = array('callee'=>'7136331642','callerId'=>'Theodis Butler');

  }  
  $callerinfo = [];
  $callerinfo = $payload['callee'];
  $callerinfo = urlencode($payload['callerId']);
  
  $data = http_build_query($callerinfo) . "&"; 
  $url = "https://dataapi.youmail.com/api/v2/{$phone}?" . $data;
  
  $options = array(
  CURLOPT_URL            => $url,
  CURLOPT_HEADER         => false,
  CURLOPT_HTTPHEADER => array("Accept:application/json","DataApiSid:{$apisid}","DataApiKey:{$apikey}"),    
  CURLOPT_VERBOSE        => false,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_USERAGENT => 'CallBlocker',
  CURLOPT_FOLLOWLOCATION => true,
  //CURLOPT_NOPROXY => '*', // do not use proxy
  CURLOPT_SSL_VERIFYPEER => false,    // for https
  CURLOPT_TIMEOUT => 5,
  CURLOPT_CONNECTTIMEOUT => 5,
  CURLOPT_REFERER => 'https://www.theodis.com'
);
// OK cool - then let's create a new cURL resource handle
$ch = curl_init();
curl_setopt_array( $ch, $options );

//curl_setopt($ch, CURLOPT_PROXY, "proxy.YOURSITE.com");
//curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
//curl_setopt ($ch, CURLOPT_PROXYUSERPWD, "username:password"); 

// Download the given URL, and return output
$output = curl_exec($ch);

// Close the cURL resource, and free system resources
curl_close($ch);

return $output;

}

function getCallerAndHangup() {

  // array of curl handles
$multiCurl = array();
// data to be returned
$result = array();
// multi handle
$mh = curl_multi_init();
$item = '';
$url1 = 'callstatus.htm?item=' .$item;
$url2 = 'callstatus.htm';
$urls = array($url1,$url2);
$url_count = count($urls);

foreach ($ids as $i => $id) {
  // URL from which data will be fetched
  $fetchURL = 'https://webkul.com&customerId='.$id;
  $multiCurl[$i] = curl_init();
  curl_setopt($multiCurl[$i], CURLOPT_URL,$fetchURL);
  curl_setopt($multiCurl[$i], CURLOPT_HEADER,0);
  curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER,1);
  curl_multi_add_handle($mh, $multiCurl[$i]);
}
$index=null;
do {
  curl_multi_exec($mh,$index);
} while($index > 0);
// get content and remove handles
foreach($multiCurl as $k => $ch) {
  $result[$k] = curl_multi_getcontent($ch);
  curl_multi_remove_handle($mh, $ch);
}
// close
curl_multi_close($mh);
}

function parseCurrentCaller($html) {

  $doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->validateOnParse = true;

$doc->loadHTML($html);
// discard white space
$doc->preserveWhiteSpace = false;
$activetable = $doc->getElementsByTagName('table')->item(0);

/*
$rows = $activetable->children(0)->children();
 
foreach($rows as $row) {
 foreach($row->children() as $column) {
  if(!empty($column->innertext)) {
   echo $column->innertext . '<br />' . PHP_EOL;
  }
 }
}
*/
$call = [];
//$rows = $tables->item(1)->getElementsByTagName('tr');
$calltable = $doc->getElementsByTagName('table')->item(1);
//echo $activetable->getElementsByTagName('tr') ;


// iterate over each row in the table
foreach($calltable->getElementsByTagName('tr') as $tr)
{
    $tds = $tr->getElementsByTagName('td'); // get the columns in this row
    if($tds->length >= 2 && !(empty($tds->item(1))))
    {
        $callinfo = $tds->item(1)->nodeValue;
        if (!empty($callinfo)) $call[] = $callinfo; // B            
    }
}
// Future parse multiple calls (i.e. second table) if active calls >=1
//var_dump($call);
// 0 - Call 1
// 1 - Terminal ID
// 2 - State
// 3 - Peer Name
// 4 - Peer Number
// 5 - Start Time
// 6 - Duration
// 7 - Direction

$activecalls = '';
$callState = $call[2];
$callerName = $call[3];
$callerNumber = $call[4];
$callStart = $call[5];
$callDuration = $call[6];
$callDirection = $call[7];

$currentcall = array($callState,$callerName,$callerNumber,$callStart,$callDuration,$callDirection);
//echo $doc->saveHTML();
return $currentcall;
}

function callHistoryJson($xmldata) {
  $xml = simplexml_load_string($xmldata, "SimpleXMLElement", LIBXML_NOCDATA);
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);

  $callhistoryjson = [];

  foreach ($array as $v => $call) {
   // $keys = array_keys($call);
   // $totalcalls = count($call);
//  var_dump($totalcalls); // 200
    foreach ($call as $caller){
      //var_dump($caller);
      //die();
      $history['Name'] = $caller['Terminal'][0]['Peer']['@attributes']['name'];
      $history['Number'] = $caller['Terminal'][0]['Peer']['@attributes']['number'];
      $history['Direction'] = $caller['Terminal'][0]['@attributes']['dir'];
      $history['Date'] = $caller['@attributes']['date'];
      $history['Time'] = $caller['@attributes']['time'];
      $history['Events'] = $caller['Terminal'][0]['Event']; // array
      $callhistoryjson[] = $history;
    }
  }

  return $callhistoryjson;
}

