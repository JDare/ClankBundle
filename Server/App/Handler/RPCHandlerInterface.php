<?php

namespace JDare\ClankBundle\Server\App\Handler;

use Ratchet\ConnectionInterface as Conn;

interface RPCHandlerInterface
{
    public function dispatch(Conn $conn, $id, $topic, array $params);
}