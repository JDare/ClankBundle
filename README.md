ClankBundle
===========

About
--------------
Clank is a Symfony2 Bundle designed to bring together WebSocket functionality in a easy to use application architecture.

Much like Socket.IO it provides both server side and client side code ensuring you have to write as little as possible to get your app up and running.

Powered By [Ratchet](http://socketo.me) and [Autobahn JS](http://autobahn.ws/js), with [Symfony2](http://symfony.com/)

Resources
--------------
* [Installation Instructions](#installation-instructions)
* [Client Javascript](Resources/docs/ClientSetup.md)
* [Server Side of RPC](Resources/docs/RPCSetup.md)
* [PubSub Topic Handlers](Resources/docs/TopicSetup.md)
* [Periodic Services](Resources/docs/PeriodicSetup.md)(functions to be run every x seconds with the IO loop.)
* [Session Management](Resources/docs/SessionSetup.md)
* [Clank Server Events](Resources/docs/ClankEvents.md)

Code Cookbook
--------------
* [Sharing Config between Server and Client](Resources/docs/code/SharingConfig.md)

Sample Projects
--------------
* [Clank Chat](http://clankchat.tabletopr.com) ([View Source](https://github.com/JDare/ClankChatBundle))
This is a simple chat room site where a user can join any channel and chat to people there.

Installation Instructions
--------------

###Step 1: Install via composer
Add the following to your composer.json

```javascript
{
    "require": {
        "jdare/clank-bundle": "0.1.*"
    }
}
```

Then update composer to install the new packages:
```command
php composer.phar update
```

###Step 2: Add to your App Kernel

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new JDare\ClankBundle\JDareClankBundle(),
    );
}
```

###Step 3: Add to Assetic Bundles

Add "JDareClankBundle" to your assetic bundles in app/config (this is required to render the client side code).

```yaml
# Assetic Configuration
assetic:
    ...
    bundles:        [ JDareClankBundle ]
```

###Step 4: Configure WebSocket Server

Add the following to your app/config.yml

```yaml
# Clank Configuration
clank:
    web_socket_server:
        port: 8080        #The port the socket server will listen on
        host: 127.0.0.1   #(optional) The host ip to bind to
```

_Note: when connecting on the client, if possible use the same values as here to ensure compatibility for sessions etc._

### Step 5: Launching the Server

The Server Side Clank installation is now complete. You should be able to run this from the root of your symfony installation.

```command
php app/console clank:server
```

If everything is successful, you will see something similar to the following:

```
Starting Clank
Launching Ratchet WS Server on: *:8080
```

This means the websocket server is now up and running!

### Next Steps

For further documentations on how to use Clank, please continue with the client side setup.

* [Setup Client Javascript](Resources/docs/ClientSetup.md)