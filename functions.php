<?php

function curl_get($Url,$username = 'admin', $password = 'admin'){
 
 // is cURL installed yet?
 if (!function_exists('curl_init')){
     die('Sorry cURL is not installed!');
 }

  // Now set some options (most are optional)
  $options = array(
    CURLOPT_URL            => $Url,
    CURLOPT_HEADER         => true,    
    CURLOPT_VERBOSE        => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => 'CallBlocker',
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_NOPROXY => '*', // do not use proxy
    CURLOPT_SSL_VERIFYPEER => false,    // for https
    CURLOPT_USERPWD        => $username . ":" . $password,
    CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
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