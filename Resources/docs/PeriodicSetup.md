#Periodic Function Services

With realtime applications, sometimes you need code to be executed regardless of events, e.g. a matchmaking engine.

With clank these can easily be added and will run within the [React Server](http://reactphp.org/) event loop.

##Step 1: Create the Periodic Service Class

Every periodic service must implement the PeriodicInterface.

```php
<?php

namespace Acme\HomeBundle\Periodic;

use JDare\ClankBundle\Periodic\PeriodicInterface;

class AcmePeriodic implements PeriodicInterface
{
    /**
     * This function is executed every 5 seconds.
     *
     * For more advanced functionality, try injecting a Topic Service to perform actions on your connections every x seconds.
     */
    public function tick()
    {
        echo "Executed once every 5 seconds" . PHP_EOL;
    }
}

```

##Step 2: Register your service with Symfony

If you are using XML, edit "YourBundle/Resources/config/services.xml", add:

```xml
 <service id="acme_hello.periodic_sample_service" class="Acme\HelloBundle\Periodic\AcmePeriodic" />
 ```

For other formats, please check the [Symfony2 Documents](http://symfony.com/doc/master/book/service_container.html)

##Step 3: Register your service with Clank

Open your "app/config/config.yml" and append the following:

```yaml
clank:
    ...
    periodic:
        -
            service: "acme_hello.periodic_sample_service" #The service id.
            time: 5000 #the time in milliseconds between the "tick" function being called
    # Add as many as you need
    #    -
    #        service: "acme_hello.periodic_other_service" #The service id.
    #        time: 1000 #the time in milliseconds between the "tick" function being called
```

The "tick" function in every periodic service will be called every X seconds depending on the value you enter into the "time" on the config.

Try pairing up a Periodic function with a [Custom Topic handler](TopicSetup.md) to perform actions on a set of clients connected to a certain topic.
