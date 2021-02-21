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

if (isset($config['youmail']['credentials']['key'])) {
  $youmailKey = $config['youmail']['credentials']['key'];
  $youmailSid = $config['youmail']['credentials']['sid'];
  $phone = $config['general']['phone'];
} else {
 die('No call checker service configured.');
}

$phonehistory = json_decode(file_get_contents('phone_history.json'), true);

/*
if (!empty($entry)) {
    
    $phonebook[$phonenumber]['Name'] = $entry['fullname'];
    $phonebook[$phonenumber]['LastCall'] = $entry['lastcalldatetime'];
    
    // add entry to phonebook if key not exists
   // file_put_contents(json_encode('phone_book.json'));
    }
*/

foreach ($phonehistory as $entry) {
    
    try {
    //$lookupdetails = youmailLookup($youmailKey,$youmailSid,$phone,$entry); 
    } catch(Exception $e){
        echo 'Error: ' . $e->getMessage();
    }
    /*
    $response = json_decode($lookupdetails,true);

    $statusCode = $response['statusCode'];
    $recordFound = $response['recordFound'];
    $phoneNumber = $response['phoneNumber'];
    $spamRiskLevel = $response['spamRisk']['level'];
    $spamRiskReason = $response['spamRisk']['reasonLabel'];
    $spamRiskCode = $response['spamRisk']['reasonCode'];
    $spamRiskMessage = $response['spamRisk']['reasonMessage'];
    $timestamp = $response['timestamp'];
    */
}