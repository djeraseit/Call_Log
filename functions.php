<?php

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