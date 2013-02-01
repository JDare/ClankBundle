<?php

namespace Jez433\ClankBundle\Server;


class EntryPoint
{
    protected $container, $servers;
    /**
     *
     */
    public function __construct($container, $servers)
    {
        $this->container = $container;
        $this->servers = $servers;
    }

    /**
     * Launches the relevant servers needed by Clank.
     */
    public function launch()
    {
        foreach($this->getServers() as $server)
        {
            //launch server into background process?
            $this->getContainer()->get($server)->launch();
        }
    }

    /**
     * Returns the service container
     *
     * @return \Symfony\Component\DependencyInjection\Container
     */
    private function getContainer()
    {
        return $this->container;
    }

    /**
     * Returns array of server services injected into this.
     *
     * @return Array
     */
    private function getServers()
    {
        return $this->servers;
    }
}