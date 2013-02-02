<?php

namespace JDare\ClankBundle\RPC;

use Ratchet\ConnectionInterface as Conn;

class AcmeService
{
    /**
     * Adds the params together
     *
     * Note: $conn isnt used here, but contains the connection of the person making this request.
     *
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param array $params
     * @return int
     */
    public function addFunc(Conn $conn, $params)
    {

        return array("result" => array_sum($params));
    }
}