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

error_reporting(E_ALL); 
ini_set( 'display_errors','1');
date_default_timezone_set('America/Chicago'); 

 // is SimpleXML installed
 if (!class_exists('SimpleXMLElement')){
    die('Sorry php-xml is not installed or php is not compiled with libxml!');
}
 // is cURL installed 
 if (!function_exists('curl_init')){
    die('Sorry cURL is not installed!');
}
return [
    'twilio' => [
        'credentials' => [
            'phone' => '+17132579496',
            'accountid' => '',
        ],
        'sid' => 'AC8bc5ced8f2c87c57ba2313501fb82b52', // use nvram
        'token' => 'cfe277e84656b6aee186301b062cfd03' // getenv("TWILIO_AUTH_TOKEN") or use nvram
    ],
    'cloudamqp' => [
        'credentials' => [
            'username' => 'rcndzvbo:rcndzvbo',
            'password' => 'E-BaZornNc1uuSID1kjuVmzDsj1UrxVY',
        ],
        'host' => "eagle.rmq.cloudamqp.com", // use nvram or eagle-01.rmq.cloudamqp.com (not loadbalanced)
        'port' => "1883", // 8883 for TLS 
        'topic' => "obihai",
        'vhost' => "rcndzvbo",
        'amqpurl' => 'amqps://rcndzvbo:E-BaZornNc1uuSID1kjuVmzDsj1UrxVY@eagle.rmq.cloudamqp.com/rcndzvbo'
    ],
    'youmail' => [
        'credentials' => [
            'key' => '6946b98eb76a1781eb15ae89df024fce9d859a8167e7012b60fd89779627521fef4eea4cceb51f73',
            'sid' => 'c54d3629988a46019dc5a863ce81d55a',
        ],
        'phone' => '7136331642', // use nvram
        'credits' => '1000'
    ],
    'obihai' => [
        'credentials' => [
            'username' => 'admin',
            'password' => 'megalith'
        ],
        'host' => '192.168.42.2',
        'scheme' => 'http',
        'poll_freq' => 2
    ],
    'numverify' => [
        'accessKey' => 'xxx',
    ],
    'pushbullet' => [
        'token' => 'o.mjCLA2hY2n5jVnwGwHrIDO76KccJtIbl',
    ],
    'general' => [
        'phone' => '7136331642',
        'phonebook' => 'phone_book.json',
        'phonehistory' => 'phone_history.json',
        'licensecode' => 'demo',
        'proxyhost' => '',
        'proxyport' => '',
        'proxyusername' => '',
        'proxypassword' => ''
    ]
];
