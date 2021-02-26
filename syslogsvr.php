<?php

/*


[Unit]
Description=Talking caller ID for OBiTALK OBi200 and Raspberry Pi (or other Linux)

[Service]
User=pi
Group=pi
ExecStart=/usr/local/bin/obicaller.sh 0.0.0.0 1514 -v en-us -s 140
Restart=always
RestartSec=30s

[Install]
WantedBy=multi-user.target

Usage

    Configure the OBi200 to send its syslog messages over the network. On the web interface, the path is System Management > Device Admin > Syslog.
    Run this script on the host and UDP port you specified in step 1.
    Done! Start taking calls.

*/

/*
if [[ $# < 2 || "$1" == '-h' || "$1" == '--help' ]]; then
        echo "Usage: $0 [-h|--help] host port [espeak args...]"
        exit 0
fi

host="$1"
port="$2"
shift 2
eargs="$@"
(python3 - $host $port <<EOF
from socketserver import BaseRequestHandler, UDPServer
from sys import argv

class PrintHandler(BaseRequestHandler):
    def handle(self):
        data = self.request[0].strip().decode('utf-8')
        print(data, flush=True)

host, port = argv[1], int(argv[2])
with UDPServer((host, port), PrintHandler) as svr:
    svr.serve_forever()
EOF
)       | grep --line-buffered --color=never -a '^<7> \[SLIC\] CID to deliver:' \
        | stdbuf -oL cut -d' ' -f6- \
        | sed -u s/+1//g \
        | sed -u "s/''/private caller/" \
        | sed -u 's/1\([0-9]\{10\}\)/\1/g' \
        | sed -u -e ':loop' -e 's/\([0-9]\)\([0-9]\)/\1 \2/g' -e 't loop' \
        | while read caller; do \
                hr=`date +%-H`; \
                if (( $hr >= 8 && $hr < 22 )); then echo $caller; fi; done \
        | while read caller; do \
                espeak $eargs "Call from $caller" --stdout | aplay 2>/dev/null; done
exit 0
*/
/* Authenticate based on hash of IP, Username, and Password */
/*
// authenticate username/password against /etc/passwd
// returns:     -1 if user does not exist
//      0 if user exists but password is incorrect
//      1 if username and password are correct
function authenticate($user, $pass)
{
    $result = -1;
    // make sure that the script has permission to read this file!
    $data = file("/etc/passwd");

    // iterate through file
    foreach ($data as $line) {
        $arr = explode(":", $line);
        // if username matches
        // test password
        if ($arr[0] == $user) {
            // get salt and crypt()
            $salt = substr($arr[1], 0, 2);
            if ($arr[1] == crypt($pass, $salt)) {
                $result = 1;
                break;
            } else {
                $result = 0;
                break;
            }
        }
    }
    // return value
    return $result;
}
*/

set_time_limit (0);
$bindaddress = "0.0.0.0";
$bindport = 9999;
$max_clients = 2;

// TODO: Use stream_socket_server instead of sockets (stream is builtin to core PHP)

/*
$sock = stream_socket_server("udp://{$bindaddress}:{$bindport}", $errno, $errstr, STREAM_SERVER_BIND);
if ($sock === false) {
    throw new UnexpectedValueException("Could not bind to socket: $errno ($errstr)");
}

do {
    $pkt = stream_socket_recvfrom($sock, 1, 0, $peer);
    echo "$peer\n";

    // don't send anything back, call function or webhook
    //stream_socket_sendto($sock, date("D M j H:i:s Y\r\n"), 0, $peer);
} while ($pkt !== false);

*/
//Create a UDP socket
if(!($sock = socket_create(AF_INET, SOCK_DGRAM, 0)))
{
	$errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    
    die("Couldn't create socket: [$errorcode] $errormsg \n");
}

echo "Socket created \n";

//Set socket options.
socket_set_nonblock($sock);
socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1);
socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);

if (defined('SO_REUSEPORT'))
socket_set_option($socket, SOL_SOCKET, SO_REUSEPORT, 1);


// Bind the source address
if( !socket_bind($sock, $bindaddress, $bindport) )
{
	$errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    
    die("Could not bind socket : [$errorcode] $errormsg \n");
}

echo "Socket bind OK \n";

$clients = [];

//Do some communication, this loop can handle multiple clients
while(true)
{
    //prepare readable sockets
	$read_socks = $clients;
	$read_socks[] = $sock;
	
	echo "Waiting for data ... \n";
	/*
    socket_recvfrom($socket, $buffer, 32768, 0, $remoteip, $remoteport) === true
            or onSocketFailure("Failed to receive packet", $socket);
    $address = "$remoteip:$rempoteport";
    if (!isset($clients[$address])) $clients[$address] = new Client();
    $clients[$address]->handlePacket($buffer);
    */

	//Receive some data
	$r = socket_recvfrom($sock, $buf, 512, 0, $remote_ip, $remote_port);
	echo "$remote_ip : $remote_port -- " . $buf;
	
	// TODO: Webhook or Pushbullet function (See which is faster)
	//socket_sendto($sock, "OK " . $buf , 100 , 0 , $remote_ip , $remote_port);
}

socket_close($sock);
