<?php

namespace JDare\ClankBundle\Server\App\Handler;

use Ratchet\ConnectionInterface as Conn;

interface TopicHandlerInterface
{
    public function onSubscribe(Conn $conn, $topic);

    public function onUnSubscribe(Conn $conn, $topic);

    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible);
}