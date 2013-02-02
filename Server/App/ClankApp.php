<?php

namespace JDare\ClankBundle\Server\App;

use Ratchet\ConnectionInterface as Conn;
use Ratchet\Wamp\WampServerInterface;

use JDare\ClankBundle\Server\App\Handler\RPCHandlerInterface;
use JDare\ClankBundle\Server\App\Handler\TopicHandlerInterface;

class ClankApp implements WampServerInterface {

    protected $topicHandler, $rpcHandler;

    public function __construct(RPCHandlerInterface $rpcHandler, TopicHandlerInterface $topicHandler)
    {
        $this->rpcHandler = $rpcHandler;
        $this->topicHandler = $topicHandler;
    }

    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible) {
        $this->topicHandler->onPublish($conn, $topic, $event, $exclude, $eligible);
    }

    public function onCall(Conn $conn, $id, $topic, array $params) {
        $this->rpcHandler->dispatch($conn, $id, $topic, $params);
    }

    //WampServer adds and removes subscribers to Topics automatically, this is for further optional events.
    public function onSubscribe(Conn $conn, $topic) {
        $this->topicHandler->onSubscribe($conn, $topic);
    }
    public function onUnSubscribe(Conn $conn, $topic) {
        $this->topicHandler->onUnSubscribe($conn, $topic);
    }

    public function onOpen(Conn $conn) {}
    public function onClose(Conn $conn) {}
    public function onError(Conn $conn, \Exception $e) {}

}