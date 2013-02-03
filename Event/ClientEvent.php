<?php
namespace JDare\ClankBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ClientEvent extends Event
{
    static public $connected = 1;
    static public $disconnected = 2;
    static public $error = 3;

    protected $conn, $type;

    public function __construct(\Ratchet\ConnectionInterface $conn, $type)
    {
        $this->conn = $conn;
        $this->type = $type;
    }

    /**
     * @return \Ratchet\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->conn;
    }

    public function getType()
    {
        return $this->type;
    }
}