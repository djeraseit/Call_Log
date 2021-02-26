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

//php pollhook.php --obihai_host 192.168.42.2 --obihai_user admin --obihai_pass megalith

set_time_limit (0);

$config = require_once(__DIR__.'/config.php');
require_once(__DIR__.'/functions.php');

$pbtoken = $config['pushbullet']['token'];
$youmailKey = $config['youmail']['credentials']['key'];
$youmailSid = $config['youmail']['credentials']['sid'];
$callee = $config['general']['phone'];
$scheme = $config['obihai']['scheme'];

$longOpts = array(
  'poll_freq:',
  'obihai_host:',
  'obihai_user:',
  'obihai_pass:',
);

$options = getopt(null, $longOpts);

if (!isset($options['obihai_host']))
{
  print "Parameter --obihai_host is mandatory!\n";
  exit(1);
}

$poll_freq = (isset($options['poll_freq']) ? $options['poll_freq'] : $config['obihai']['poll_freq']);

$obihai_host = (isset($options['obihai_host']) ? $options['obihai_user'] : $config['obihai']['credentials']['host']);
$obihai_user = (isset($options['obihai_user']) ? $options['obihai_user'] : $config['obihai']['credentials']['username']);
$obihai_pass = (isset($options['obihai_pass']) ? $options['obihai_pass'] : $config['obihai']['credentials']['password']);

/*
$config = array(
  'indent' => true,
  'clean' => true,
  'input-xml'  => true,
  'output-xml' => true,
  'wrap'       => false
  );

$tidy = new Tidy();
$xml = $tidy->repairfile($badXML, $config);
echo $xml;
*/
//$searchPage = mb_convert_encoding($htmlUTF8Page, 'HTML-ENTITIES', "UTF-8"); 

// 2 is phone line from telco (FXS); 1 is from obihai to phone (FXO) 

$j = 1;

while (true)
{
$pagename = "PI_FXS_1_Stats.xml"; // "&time=".time(); //add time to cache
$url = $scheme . "://" . $obihai_host . "/" . $pagename;
try {
$output = curl_get($url, $obihai_user, $obihai_pass);
} catch (Exception $e) {
  echo $e->getMessage();
}

$states = array();
  try {
  $xml = new SimpleXMLElement($output, LIBXML_NOERROR |  LIBXML_ERR_NONE);
} catch (Exception $e){
  echo $e->getMessage();
}

  $parameters = $xml->xpath('//parameter');
  foreach ($parameters as $parameter)
  {
    foreach ($parameter->attributes() as $attribute => $value)
    {
      if ($attribute == 'name' && $value == 'State')
      {
        $states[] = $parameter->value['current'];
      }
    }
  }
  
  $callerinfo = "";
  $pushbullet = "";
  $pbsent = true;

    $i = 1;
    

    foreach ($states as $state)
    {
      // TODO: need to do something like call block when Ringing, and when Off Hook start recording, when On Hook, get last caller, etc
        if ($state == 'Ringing') {
          sleep(1); //sleep for a second to give time for the caller information to show up. (or check appropriate line that is ringing)
          // NOTE: if line 2 is ringing and Line 1 is not ringing then the Obihai has dropped the call internally based on dial plan
          try {
            $callerinfo = json_decode(getCallerAndLookup($obihai_host, $obihai_user, $obihai_pass, $state),true);
          } catch (Exception $e) {
            echo $e->getMessage();
          }

          if (isset($callerinfo['Number'])) {
            $callername = $callerinfo['Name'];
            $callernumber = $callerinfo['Number'];
            $callerstart = $callerinfo['StartTime'];
            $callerdirection = $callerinfo['Direction'];
            $calleritem = $callerinfo['Item'];
            $callerformatted = $callername . ' - ' . $callernumber; 
            //$hangup = hangup($calleritem);
            // TODO: Get SPAM score if Direction is Inbound and phonebook spamscore is null
           
            $payload = array('body'=>$callerformatted,'title'=>'INCOMING CALL','type'=>'note');
            $youmailpayload = array('callee'=>$callee,'callerId'=>$callername);
            // nested loop
            while($j <= 1){
              // Check phonebook 
              $phonebookentry = json_decode(checkPhonebook($callernumber, $callername), true);
              if (!empty($phonebookentry) && isset($phonebookentry[$callernumber]['spamRisk'])){
               // print_r($phonebookentry);
               $spamRisk = $phonebookentry[$callernumber]['spamRisk'];
              } else {
                $phonebookdata = array('Name'=>$callername);
              }

              if ($spamRisk == 1) {
                hangup($calleritem);
              } elseif($spamRisk == null || !isset($spamRisk)){

              // Lookup spam score and combine array
              try{
                $youmailresults = youmailLookup($youmailKey,$youmailSid,$callernumber,$youmailpayload);
                $youmailinfo = json_decode($youmailresults,true);
                $spamScore = $youmailinfo['spamRisk']['level'];
              } catch(Exception $e) {
                echo $e->getMessage();
              }             
            } // end elseif
              
              // level 2 is strong evidence spammer, level 1 is appears to be spammer
              if ($spamScore == 1 || $spamRisk == 1) {
                hangup($calleritem);
              }
                // Do pushbullet once
              try {
                    $pushbullet = pb_alert($pbtoken, $payload);
                    $pbsent = false;
               } catch (Exception $e){
                    echo $e->getMessage();
              }
            
              $pbresult = json_decode($pushbullet,true);
                if ($pbresult['active'] == true && !empty($callernumber)){
                  echo "PUSHBULLET SENT!";
                } 
              // TODO: Consolidate spamScore and spamRisk to a final score
                if (isset($spamScore) || isset($spamRisk)){
                  // push spamRisk onto array
                  $phonebookdata['spamRisk'] = $spamRisk;
                  //$phonebookupdates = array_merge($phonebookdata,array('spamRisk'=>$spamRisk));
                }
              // This is part of the checkPhonebook function (add all info to phonebook last)
              $entryresults = addPhonebook($callernumber,$phonebookdata);
              // This will
              var_dump($entryresults);
                $j++;
            }
            // end nested loop
      
          }
          // Part of outer loop   
         
          // end part of outer loop      
        }
              echo $state . ' ' . $i . PHP_EOL; // On Hook, Ringing, Off Hook
              //print_r($callerinfo);   
             // echo $pushbullet;   
      $i++;
    }    
  sleep($poll_freq);
}