<?php

namespace JDare\ClankBundle\Server\Type;

use JDare\ClankBundle\Periodic\PeriodicInterface;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;


class WebSocketServerType implements ServerTypeInterface
{
    protected $app, $server, $loop, $socket, $host, $port, $periodicServices, $container;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;

        $this->periodicServices = array();


    }

    public function launch()
    {
        $this->setupServer();

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
        $this->app = new WsServer(
            new WampServer(
                $this->getContainer()->get("jdare_clank.clank_app")
            )
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


}