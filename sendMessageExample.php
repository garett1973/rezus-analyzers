<?php

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);


$connection = @socket_connect($socket, '127.0.0.1', 12000);
    if ($connection) {

        $header_maglumi = 'H|\^&||PSWD|Maglumi User|||||Lis||P|E1394-97|' . date('Ymd');
        $header_premier = 'H|\^&|||PREMIER^HB9210||||||ASTM RECVR|||P|E 1394-97|' . date('Ymd');

        $random = rand(0, 1);

        if ($random == 0) {
            $header = $header_maglumi;
        } else {
            $header = $header_premier;
        }

        $message = bin2hex($header);
        if ($header == $header_premier) {
            $message = $message . 13;
        }

        // calculate checksum adding the binary values of all characters in the message
        $checksum = 0;
        for ($i = 0; $i < strlen($message); $i++) {
            $checksum += ord($message[$i]);
        }

        // keep only the least significant byte of the checksum
        $checksum = $checksum & 0xFF;

        // express the checksum in hexadecimal by two ascii characters
        $checksum = dechex($checksum);

        // add the checksum to the message
        $message = $message . $checksum;

        echo "Sending message to the server: $message\n";
        if ($header == $header_maglumi) {
            echo "Sending message to the server: " . hex2bin(substr($message, 0, -2)) . "\n";
        } else {
            echo "Sending message to the server: " . hex2bin(substr($message, 0, -4)) . "\n";
        }

        // Send $message over the socket
        socket_write($socket, $message, strlen($message));

        // Read the response from the server
        $response = socket_read($socket, 1024); // Adjust buffer size as needed
        echo "Received response from server: $response\n";

    } else {
        echo "Error connecting to server: " . socket_strerror(socket_last_error($socket)) . "\n";
    }

socket_close($socket);
