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
            'phone' => '+15555555555',
            'accountid' => '',
        ],
        'sid' => '', // use nvram
        'token' => '', // getenv("TWILIO_AUTH_TOKEN") or use nvram
        'apikey' => ''
    ],
    'cloudamqp' => [
        'credentials' => [
            'username' => '',
            'password' => 'E-',
        ],
        'host' => "", // use nvram or eagle-01.rmq.cloudamqp.com (not loadbalanced)
        'port' => "1883", // 8883 for TLS 
        'topic' => "obihai",
        'vhost' => "",
        'amqpurl' => ''
    ],
    'youmail' => [
        'credentials' => [
            'key' => '',
            'sid' => '',
        ],
        'phone' => '5555555555', // use nvram
        'credits' => '1000'
    ],
    'obihai' => [
        'credentials' => [
            'username' => 'admin',
            'password' => 'admin'
        ],
        'host' => '192.168.1.2',
        'scheme' => 'http',
        'poll_freq' => 1
    ],
    'numverify' => [
        'accessKey' => 'xxx',
    ],
    'pushbullet' => [
        'token' => 'o',
    ],
    'cloudflare' => [
        'accountid' => '',
        'authemail' => '',
        'authkey' => '',
        'namespace' => 'obihai',
        'namespaceid' => ''
    ],
    'general' => [
        'phone' => '5555555555',
        'phonebook' => 'phone_book.json',
        'phonehistory' => 'phone_history.json',
        'whitelistnumbers' => 'whitelist_numbers.json',
        'whitelistnames' => 'whitelist_names.json',
        'blacklistnumbers' => 'blacklist_numbers.json',
        'blacklistnames' => 'blacklist_names.json',
        'licensecode' => 'demo',
        'proxy'=>
			[
			'enabled' => 0,
			'proxyhost' => '',
			'proxyport' => '',
			'proxyusername' => '',
			'proxypassword' => '']
			]
];
