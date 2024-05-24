<?php

$serverSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if ($serverSocket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "Socket created.\n";
}

$ipAddress = '127.0.0.1';
$port = 8080;
socket_bind($serverSocket, $ipAddress, $port);

if ($serverSocket === false) {
    echo "socket_bind() failed\n ";
} else {
    echo "Socket bind OK.\n";
}

socket_listen($serverSocket, 3);

echo "Server listening on $ipAddress:$port\n";

// Accept incoming connections
    while (true) {
        $clientSocket = socket_accept($serverSocket);
        echo "New client connected.\n";

        // Read the message from the client socket
        $message = socket_read($clientSocket, 1024);
        echo "Message received: $message\n";

        // Close the client socket
        socket_close($clientSocket);
    }
socket_close($serverSocket);
