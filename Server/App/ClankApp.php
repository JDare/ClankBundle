<?php

namespace Jez433\ClankBundle\Server\App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ClankApp implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {
    }

    public function onMessage(ConnectionInterface $from, $msg) {
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}