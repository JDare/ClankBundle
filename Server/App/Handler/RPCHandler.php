<?php

namespace Jez433\ClankBundle\Server\App\Handler;

use Ratchet\ConnectionInterface as Conn;

class RPCHandler implements RPCHandlerInterface
{
    public function dispatch(Conn $conn, $id, $topic, array $params)
    {

    }
}