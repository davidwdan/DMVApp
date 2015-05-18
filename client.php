<?php

require __DIR__ . '/vendor/autoload.php';


// Create a new Thruway Client
$client = new \Thruway\Peer\Client('realm1');

// Add a Websocket transport provider
$client->addTransportProvider(new \Thruway\Transport\PawlTransportProvider('ws://127.0.0.1:8989'));


// Listen to the open event
$client->on('open', function (\Thruway\ClientSession $session) {

    $line       = [];
    $nowServing = 0;

    // Register the RPC 'get_in_line' and tell it to disclose the caller information
    $session->register('get_in_line', function ($args, $argskw, $details) use (&$line) {

        if (!in_array($details->caller, $line)) {
            return array_push($line, $details->caller);
        } else {
            return array_search($details->caller, $line) + 1;
        }

    }, ["disclose_caller" => true]);


    // Register the RPC `next`, which advances the nowServing counter and publishes the number currently being servered to the topic `line_change`
    $session->register('next', function () use (&$nowServing, &$line, $session) {
        $nowServing++;

        // Publish to the topic `line_change`.  The other clients will listen on this topic
        $session->publish('line_change', [$nowServing]);

        // RPCs can return values
        return $nowServing;

    });


    $session->register('clear', function () use (&$nowServing, &$line, $session) {
        $nowServing = 0;
        $session->publish('line_change', [$nowServing]);
        $line = [];

        return $nowServing;

    });


    // When a client connects, we want to be able to provide a default value for the `line_change` topic.
    // This works by registering a handler for the topic with the router.
    // When a client subscribes to the topic, the router will first the RPC uri `line_change_handler`,
    // which allows us to publish the default value to just that session.
    $session->call("add_state_handler", [["uri" => "line_change", "handler_uri" => "line_change_handler"]]);

    $session->register("line_change_handler", function ($res) use ($session, &$nowServing) {
        $options = ["eligible" => $res[1]];
        $session->publish('line_change', [$nowServing], [], $options);
    });

});


//Start the client.  The Program blocks at this point, so don't add anything after this line.
$client->start();


