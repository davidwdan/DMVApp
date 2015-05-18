<?php

require __DIR__ . '/vendor/autoload.php';


$realm = "realm1";

$router = new \Thruway\Peer\Router();

//Enable a Websocket transport provider
$router->addTransportProvider(new \Thruway\Transport\RatchetTransportProvider("127.0.0.1", "8989"));

//Enable Topic State Handler Module - This allows the router to register RPCs that provide a default state for topics
$router->registerModule(new \Thruway\Subscription\StateHandlerRegistry($realm, $router->getLoop()));

$router->start();

