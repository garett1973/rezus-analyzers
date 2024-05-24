<?php
function listen() {
    // Set time limit to indefinite execution
    set_time_limit(0);

    // Set the IP and port we will listen on
    $address = 'XX.XX.XX.XXX'; // Replace with your desired IP address
    $port = 12000; // Replace with your desired port

    // Create a TCP Stream socket
    $sock = socket_create(AF_INET, SOCK_STREAM, 0);

    // Bind the socket to an address/port
    $bind = socket_bind($sock, $address, $port);

    // Start listening for connections
    socket_listen($sock);

    // Keep accepting incoming requests and handle them
    while (true) {
        $client = socket_accept($sock);
        $input = socket_read($client, 2024);

        // Check if the input is 'exit' to close the listening
        if ($input === 'exit') {
            socket_close($sock);
            break;
        }

        // Process the received data (e.g., echo it)
        echo $input;
    }
}

listen();

