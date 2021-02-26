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


if (isset($config['obihai']['host'])) {
    $host = $config['obihai']['host'];
    $username = $config['obihai']['credentials']['username'];
    $password = $config['obihai']['credentials']['password'];
    $scheme = $config['obihai']['scheme'];
    $sid = $config['twilio']['apikey']['sid'];
    $secret = $config['twilio']['apikey']['secret'];

  } else {
 die('Software must be configured.');
 }
$phonenumber = '17136499932';
try {
 $spamresult = twilioNomorobo($sid, $secret, $callee = '+17136331642', $phonenumber);
} catch (Exception $e) {
    echo $e->getMessage();
}
echo $spamresult;
/*
{"caller_name": null, "country_code": "US", "phone_number": "+17136499932", "national_format": "(713) 649-9932", "carrier": null, "add_ons": {"status": "successful", "message": null, "code": null, "results": {"nomorobo_spamscore": {"status": "successful", "request_sid": "XR6bef232676734d863c84fa63060c1db6", "message": null, "code": null, "result": {"status": "success", "message": "success", "score": 0, "neighbor_score": 0}}}}, "url": "https://lookups.twilio.com/v1/PhoneNumbers/+17136499932"}
*/