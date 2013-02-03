#Clank Events

Sometimes you will need to perform a server side action when a user connects or disconnects. Clank will fire events for 3 reasons:

* Client Connects
* Client Disconnects
* On Socket Error

By utilising Symfony2 Event Listeners, you can be notified when any of these events occur.

###Step 1: Create Event Listener Class

Create a [Symfony 2 event listener class](http://symfony.com/doc/current/cookbook/service_container/event_listener.html)

```php
<?php
namespace Acme\HelloBundle\EventListener;

use JDare\ClankBundle\Event\ClientEvent;
use JDare\ClankBundle\Event\ClientErrorEvent;

class AcmeClientEventListener
{
    /**
     * Called whenever a client connects
     *
     * @param ClientEvent $event
     */
    public function onClientConnect(ClientEvent $event)
    {
        $conn = $event->getConnection();

        echo $conn->resourceId . " connected" . PHP_EOL;
    }

    /**
     * Called whenever a client disconnects
     *
     * @param ClientEvent $event
     */
    public function onClientDisconnect(ClientEvent $event)
    {
        $conn = $event->getConnection();

        echo $conn->resourceId . " disconnected" . PHP_EOL;
    }

    /**
     * Called whenever a client errors
     *
     * @param ClientErrorEvent $event
     */
    public function onClientError(ClientErrorEvent $event)
    {
        $conn = $event->getConnection();
        $e = $event->getException();

        echo "connection error occurred: " . $e->getMessage() . PHP_EOL;
    }

}
```

###Step 2: Register it as a service

Add this to your bundles "services.xml"

_Note: the 3 events to listen for in the tags are: "clank.client.connected", "clank.client.disconnected", "clank.client.error"._
```xml
<service id="kernel.listener.clank.client_event" class="Acme\HelloBundle\EventListener\AcmeClientEventListener">
    <tag name="kernel.event_listener" event="clank.client.connected" method="onClientConnect" />
    <tag name="kernel.event_listener" event="clank.client.disconnected" method="onClientDisconnect" />
    <tag name="kernel.event_listener" event="clank.client.error" method="onClientError" />
</service>
```

You will now notice that when a user connects or disconnects from your server, you will be given a notification in the command line.