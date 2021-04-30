<?php
    //Code by: Nabi KAZ <www.nabi.ir>
    
    // set some variables
    $host = "127.0.0.1";
    $port = 8000;
    session_start();
    // don't timeout!
    set_time_limit(0);
    
    // create socket
    $socket = socket_create(AF_INET, SOCK_STREAM, 0)or die("Could not create socket\n");
    
    // bind socket to port
    $result = socket_bind($socket, $host, $port)or die("Could not bind to socket\n");
    
    // start listening for connections
    $result = socket_listen($socket, 20)or die("Could not set up socket listener\n");
    var_dump($result);
    
    $flag_handshake = false;
    $client = null;
    do {
        if (!$client) {
            // accept incoming connections
            // client another socket to handle communication
            $client = socket_accept($socket)or die("Could not accept incoming connection\n");
            var_dump($client);
        }
    
        $bytes =  @socket_recv($client, $data, 2048, 0);
        if ($flag_handshake == false) {
            if ((int)$bytes == 0)
                continue;
            //print("Handshaking headers from client: ".$data."\n");
            if (handshake($client, $data, $socket)) {
                $flag_handshake = true;
            }
        }
        elseif($flag_handshake == true) {
            if ($data != "") {
                $decoded_data = unmask($data);
                print("< ".$decoded_data."\n");
                $response = strrev($decoded_data);
                socket_write($client, encode($response));
                print("> ".$response."\n");

                include_once "db_connection.php";
    
                if(true){
                    $outgoing_id = 6; 
                    $incoming_id = 7;
                    $message = $decoded_data;
                    $conn = OpenCon();

                    if(!empty($message)){
                        $sql = mysqli_query($conn, "INSERT INTO chat_messages (incoming_msg_id, outgoing_msg_id, msg) 
                        VALUES({$incoming_id}, {$outgoing_id}, '{$message}')") or die();
                    }
                    // closeCon($conn);
                }else{
                    echo "logout";
                    exit;
                }


                // socket_close($client);
                // $client = null;
                // $flag_handshake = false;
            }
        }
    } while (true);
    
    // close sockets
    socket_close($client);
    socket_close($socket);
    closeCon($conn);
    
    function handshake($client, $headers, $socket) {
    
        if (preg_match("/Sec-WebSocket-Version: (.*)\r\n/", $headers, $match))
            {$version = $match[1];
            // var_dump($headers);
            // var_dump($version);
            }
        else {
            print("The client doesn't support WebSocket");
            return false;
        }
    
        if ($version == 13) {
            // Extract header variables
            if (preg_match("/GET (.*) HTTP/", $headers, $match))
                $root = $match[1];
                var_dump($root);
            if (preg_match("/Host: (.*)\r\n/", $headers, $match))
                $host = $match[1];
                var_dump($host);
            if (preg_match("/Origin: (.*)\r\n/", $headers, $match))
                $origin = $match[1];
                var_dump($origin);
            if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $headers, $match))
                $key = $match[1];
                var_dump($key);
    
            $acceptKey = $key.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
            $acceptKey = base64_encode(sha1($acceptKey, true));
    
            $upgrade = "HTTP/1.1 101 Switching Protocols\r\n".
                "Upgrade: websocket\r\n".
                "Connection: Upgrade\r\n".
                "Sec-WebSocket-Accept: $acceptKey".
                "\r\n\r\n";
    
            socket_write($client, $upgrade);
            return true;
        } else {
            print("WebSocket version 13 required (the client supports version {$version})");
            return false;
        }
    }
    
    function unmask($payload) {
        $length = ord($payload[1]) & 127;
    
        if ($length == 126) {
            $masks = substr($payload, 4, 4);
            $data = substr($payload, 8);
        }
        elseif($length == 127) {
            $masks = substr($payload, 10, 4);
            $data = substr($payload, 14);
        }
        else {
            $masks = substr($payload, 2, 4);
            $data = substr($payload, 6);
        }
    
        $text = '';
        for ($i = 0; $i < strlen($data); ++$i) {
            $text .= $data[$i] ^ $masks[$i % 4];
        }
        return $text;
    }
    
    function encode($text) {
        // 0x1 text frame (FIN + opcode)
        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($text);
    
        if ($length <= 125)
            $header = pack('CC', $b1, $length);
        elseif($length > 125 && $length < 65536)$header = pack('CCS', $b1, 126, $length);
        elseif($length >= 65536)
        $header = pack('CCN', $b1, 127, $length);
    
        return $header.$text;
    }
    
    
    
    
    // $host = "127.0.0.1";
    // $port = 80;
    // set_time_limit(0);

    // $sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("could not create socket\n");
    // $result = socket_bind($sock, $host, $port) or die("could not bind to socket\n");

    // $result = socket_listen($sock, 3) or die("could not create socket listener\n");
    // echo "listening for connections";

    // class Chat
    // {
    //     function readline()
    //     {
    //         return rtrim(fgets(STDIN));
    //     }
    // }

    // do
    // {
    //     $accept = socket_accept($sock) or die("could not accept incoming connection\n");
    //     $msg = socket_read($accept, 1024) or die("could not read input\n");

    //     $msg = trim($msg);
    //     echo "client says:\t". $msg . "\n\n";

    //     $line = new Chat();
    //     echo "enter reply:\t";
    //     $reply = $line->readline();

    //     socket_write($accept, $reply, strlen($reply)) or die("could not write output\n");
    // }while(true);

    // socket_close($accept, $sock);
?>