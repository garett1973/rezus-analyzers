<?php

use App\Models\Maglumi_4000_Plus;

$serverSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
$ipAddress = '127.0.0.1'; // Replace with your desired IP address
$port = 12000; // Replace with your desired port

socket_bind($serverSocket, $ipAddress, $port);
socket_listen($serverSocket);

echo "Server listening on $ipAddress:$port\n";

// List of allowed IP addresses
$allowedIPs = ['127.0.0.1', '192.168.1.100']; // Add more as needed

while (true) {
    $clientSocket = socket_accept($serverSocket);
    $clientIP = socket_getpeername($clientSocket, $clientPort);

    if (in_array($clientIP, $allowedIPs)) {
        echo "New client connected from $clientIP.\n";

        // Read data from the client as hexadecimal string
        $receivedData = socket_read($clientSocket, 1024); // Read data from the socket
        echo "Received data from client: $receivedData\n";

        $header_maglumi = bin2hex('H|\^&||PSWD|Maglumi User|||||Lis||P|E1394-97|' . date('Ymd'));
        $header_premier = bin2hex('H|\^&|||PREMIER^HB9210||||||ASTM RECVR|||P|E 1394-97|' . date('Ymd')) . 13;

        if ($receivedData) {
            // get two last characters of the received data as checksum
            $receivedChecksum = substr($receivedData, -2);

            // remove the checksum from the received data
            $receivedData = substr($receivedData, 0, -2);
            echo "Received data without checksum: $receivedData\n";
            echo "Received checksum: $receivedChecksum\n";
        }

        if ($receivedData == $header_maglumi) {
            $response = "This is a Snibe Maglumi! Hello from the server! Your IP is $clientIP.";
            socket_write($clientSocket, $response, strlen($response));
        } elseif ($receivedData == $header_premier) {
            $response = "This is a Premier HB9210! Hello from the server! Your IP is $clientIP.";
            socket_write($clientSocket, $response, strlen($response));
        } else {
            echo "Unauthorized client connection from $clientIP. Closing socket.\n";
            socket_close($clientSocket);
        }
    } else {
        echo "Unauthorized client connection from $clientIP. Closing socket.\n";
        socket_close($clientSocket);
    }
}

// Close the server socket (not reached in this example)
socket_close($serverSocket);

