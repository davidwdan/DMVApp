# DMV Demo

This is the demo app that was built during my [Thruway](https://github.com/voryx/Thruway) presentation at [DC PHP Developer's Community](http://www.meetup.com/DC-PHP/events/221406542/)


## Overview

This is a very simple fake application that lets you get in line before you arrive at the DMV.

##Installation

[Download](https://github.com/davidwdan/DMVApp/archive/master.zip) and unzip this project.

Install the PHP dependencies

`composer install`

Install JS dependencies

`cd web_client`

`bower install`


##Start

Since you'll be running PHP as a long running process, you'll need to make sure that you have an environment setup with php-cli.  For obvious reasons, you do not want to run a PHP long running process with apache.

You'll need to start the Thruway router and the client from the command prompt.

`php router.php`

`php client.php`


Then point your web server to the ./web_client directory and access `index.html` for the user's client and `clerk.html` for the clerk's page (I used an Ardunio with an LCD screen for this part).


##More reading

[WAMP](http://wamp.ws) is a very robust protocol, so take some time to read up on the specification.

Thruway is built on [ReactPHP](http://reactphp.org/).  Cees-Jan has written a [series of blog posts](http://blog.wyrihaximus.net/2015/01/reactphp-introduction/) on using the different React libraries.

Thruway also uses [Ratchet](http://socketo.me/) for its websocket transport.  Most of the ratchet interaction is abstracted away, but the [deployment](http://socketo.me/docs/deploy) documentation still applies to Thruway. 
