<?php

namespace JDare\ClankBundle\Server\Type;

interface ServerTypeInterface
{
    /**
     *
     * @param string $host
     * @param int $port
     */
    public function __construct($host, $port);

    /**
     * Launches the server loop
     *
     * @return void
     */
    public function launch();

    /**
     * Returns a string of the host:port for debugging / display purposes
     * @return string
     */
    public function getAddress();

    /**
     * Returns a string of the name of the server/service for debugging / display purposes
     * @return string
     */
    public function getName();
}