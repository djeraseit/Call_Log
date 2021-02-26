<?php 

/**
 * I was having trouble with socket connections timing out reliably. Sometimes,
 * my timeout would be reached. Other times, the connect would fail after three
 * to six seconds. I finally figured out it had to do with trying to connect to
 * a routable, non-localhost address. It seems the socket_connect call would
 * not fail immediately for those connections. This function is what I finally
 * ended up with that reliably connects to a working server, fails quickly for
 * a server that has an address/port that is not reachable and will reach the
 * timeout for routable addresses that are not up.
 *
 * Full Story: http://brian.moonspot.net/socket-connect-timeout
 *
 * Copyright (c) 2015, Brian Moon of DealNews.com, Inc.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *  * Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  * Redistributions in binary form must reproduce the above
 *    copyright notice, this list of conditions and the following
 *    disclaimer in the documentation and/or other materials provided
 *    with the distribution.
 *  * Neither the name of DealNews.com Inc. nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

/**
 * Example:
 *
 * Assuming these hosts are valid for your setup, this example would produce
 * something like this:
 *
 * Trying: Good host...
 * resource(10) of type (Socket)
 *
 * Trying: Routable, but not up...
 * Failed to connect to 10.1.2.30:4730. (timed out after 102.1051ms)
 * NULL
 *
 * Trying: Up but not listening...
 * Failed to connect to 127.0.0.1:7676. (111: Connection refused; after 0.051ms)
 * NULL
 *
 * =====
 *
 * $timeout = 100;
 *
 * $hosts = array(
 *     array(
 *         "desc" => "Good host",
 *         "host" => "127.0.0.1",
 *         "port" => "4730"
 *     ),
 *     array(
 *         "desc" => "Routable, but not up",
 *         "host" => "10.1.2.30",
 *         "port" => "4730"
 *     ),
 *     array(
 *         "desc" => "Up but not listening",
 *         "host" => "127.0.0.1",
 *         "port" => "7676"
 *     ),
 * );
 *
 * foreach($hosts as $host){
 *
 *     echo "Trying: $host[desc]...\n";
 *
 *     try{
 *         $socket = socket_connect_timeout($host["host"], $host["port"], $timeout);
 *     } catch(Exception $e){
 *         echo $e->getMessage()."\n";
 *         $socket = null;
 *     }
 *
 *     var_dump($socket);
 *
 *     echo "\n";
 *
 * }
 *
 */

$config = require __DIR__.'/config.php';
require_once(__DIR__.'/functions.php');

$port = 80;
$host = "192.168.42.2";
$timeout = 100;

$result = null;
try {
$result = socket_connect_timeout($host, $port, $timeout);
} catch (Exception $e) {
echo $e->getMessage();
}

//var_dump($result);