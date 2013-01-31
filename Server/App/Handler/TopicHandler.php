<?php

namespace Jez433\ClankBundle\Server\App\Handler;

use Ratchet\ConnectionInterface as Conn;

class TopicHandler implements TopicHandlerInterface
{
    public function onSubscribe(Conn $conn, $topic) {}

    public function onUnSubscribe(Conn $conn, $topic) {}

    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible) {
        $topic->broadcast($event);
    }
}