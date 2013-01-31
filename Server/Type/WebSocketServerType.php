<?php

namespace Jez433\ClankBundle\Server\Type;

use Jez433\ClankBundle\Service\PeriodicInterface;
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
        foreach($this->periodicServices as $serviceId => $timer)
        {
            $service = $this->getContainer()->get($serviceId);
            if (!($service instanceof PeriodicInterface))
            {
                throw new \Exception("Periodic Services must implement Jez433/ClankBundle/Service/PeriodicInterface");
            }
            $this->loop->addPeriodicTimer(($timer/1000), array($service, "tick"));
        }
    }

    /**
     * Sets up clank app to bootstrap Ratchet and handle socket requests
     */
    private function setupApp()
    {
        $this->app = new WsServer(
            $this->getContainer()->get("jez433_clank.clank_app")
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


}