<?php

declare(strict_types=1);

namespace FaltLeap;

class LeapWebSocketServer
{
    public function start()
    {
        $host = '0.0.0.0';
        $port = 8080;

        $server = stream_socket_server("tcp://$host:$port", $errno, $errstr);

        if (!$server) {
            exit("Error: $errstr ($errno)\n");
        }

        echo "WebSocket server listening on $host:$port\n";

        while ($conn = @stream_socket_accept($server, -1)) {
            // Read handshake request
            $request = fread($conn, 1500);

            // Extract Sec-WebSocket-Key
            if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $request, $matches)) {
                $key = trim($matches[1]);
                $accept = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
                // Send handshake response
                $response = "HTTP/1.1 101 Switching Protocols\r\n"
                    . "Upgrade: websocket\r\n"
                    . "Connection: Upgrade\r\n"
                    . "Sec-WebSocket-Accept: $accept\r\n\r\n";
                fwrite($conn, $response);

                // Read a frame (very basic, assumes text frame, no fragmentation)
                $data = fread($conn, 512);
                if ($data) {
                    // Unmask payload
                    $payloadLen = ord($data[1]) & 127;
                    $mask = substr($data, 2, 4);
                    $payload = substr($data, 6, $payloadLen);
                    $unmasked = '';
                    for ($i = 0; $i < $payloadLen; $i++) {
                        $unmasked .= $payload[$i] ^ $mask[$i % 4];
                    }
                    echo "Received: $unmasked\n";

                    // Echo back (send as text frame)
                    $reply = chr(0x81) . chr(strlen($unmasked)) . $unmasked;
                    fwrite($conn, $reply);
                }
            }
            fclose($conn);
        }
    }

}
