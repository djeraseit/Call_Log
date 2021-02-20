<?php

require_once __DIR__ .'/vendor/autoload.php';

use Twilio\Rest\Client;

// Find your Account Sid and Auth Token at twilio.com/console
// and set the environment variables. See http://twil.io/secure
$sid = getenv("TWILIO_ACCOUNT_SID");
$token = getenv("TWILIO_AUTH_TOKEN");
$twilio = new Client($sid, $token);

$phone_number = $twilio->lookups->v1->phoneNumbers("+15108675310")
                                    ->fetch(["type" => ["carrier"]]);

print($phone_number->carrier);
