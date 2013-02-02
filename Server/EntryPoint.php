<?php

namespace JDare\ClankBundle\Server;

use Symfony\Component\Console\Output\OutputInterface;
use JDare\ClankBundle\Server\Type\ServerTypeInterface;

class EntryPoint
{
    protected $container, $servers, $output;

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }
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
            $server = $this->getContainer()->get($server);
            if (!$server)
            {
                throw new \Exception("Unable to find Server Service.");
            }

            if (!($server instanceof ServerTypeInterface))
            {
                throw new \Exception("Server Service must implement ServerTypeInterface");
            }

            if ($this->getOutput())
            {
                $this->getOutput()->writeln("Launching " . $server->getName() . " on: " . $server->getAddress());
            }
            //launch server into background process?
            $server->launch();
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