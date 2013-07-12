<?php

namespace JDare\ClankBundle\Server\Type;

use JDare\ClankBundle\Periodic\PeriodicInterface;
use JDare\ClankBundle\Event\ServerEvent;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;
use Ratchet\Session\SessionProvider;

class WebSocketServerType implements ServerTypeInterface
{
    protected $app, $server, $loop, $socket, $host, $port, $periodicServices, $session, $container;

    public function __construct($host, $port)
    {
        $this->session = false;
        $this->host = $host;
        $this->port = $port;

        $this->periodicServices = array();
    }
    
    public function getLoop() {
        return $this->loop;
    }

    public function launch()
    {
        $this->setupServer();
        
        /* Server Event Loop to add other services in the same loop. */
        $event = new ServerEvent($this->loop);
        $this->container->get("event_dispatcher")->dispatch("clank.server.launched", $event);        

        $this->loop->run();
    }

    /**
     * Sets up loop and server manually to allow periodic timer calls.
     */
    private function setupServer()
    {
        $this->setupApp();

        /** @var $loop \React\EventLoop\LoopInterface */

        $this->loop = \React\EventLoop\Factory::create();
        
        $this->socket = new \React\Socket\Server($this->loop);

        if ($this->host)
        {
            $this->socket->listen($this->port, $this->host);
        }else{
            $this->socket->listen($this->port);
        }

        $this->setupPeriodicServices();

        $this->server = new \Ratchet\Server\IoServer($this->app, $this->socket, $this->loop);
    }

    private function setupPeriodicServices()
    {
        foreach($this->periodicServices as $pService)
        {
            $service = $this->getContainer()->get($pService['service']);
            if (!($service instanceof PeriodicInterface))
            {
                throw new \Exception("Periodic Services must implement JDare/ClankBundle/Periodic/PeriodicInterface");
            }
            $this->loop->addPeriodicTimer(($pService['time']/1000), array($service, "tick"));
        }
    }

    /**
     * Sets up clank app to bootstrap Ratchet and handle socket requests
     */
    private function setupApp()
    {
        if ($this->session instanceof \SessionHandlerInterface)
        {
            $serverStack = new SessionProvider(
                new WampServer(
                    $this->getContainer()->get("jdare_clank.clank_app")
                ),
                $this->session
            );

        }else{
            $serverStack = new WampServer(
                $this->getContainer()->get("jdare_clank.clank_app")
            );
        }


        $this->app = new WsServer(
            $serverStack
        );
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function setPeriodicServices($services)
    {
        $this->periodicServices = $services;
    }

    public function getAddress()
    {
        return (($this->host)?$this->host:"*") . ":" . $this->port;
    }

    public function getName()
    {
        return "Ratchet WS Server";
    }

    public function setSession($session)
    {
        if ($session)
            $session = $this->getContainer()->get($session);

        if ($session instanceof \SessionHandlerInterface)
            $this->session = $session;
    }

    public function getSession()
    {
        return $this->session;
    }

}