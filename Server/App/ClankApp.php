<?php

namespace JDare\ClankBundle\Server\App;

use Ratchet\ConnectionInterface as Conn;
use Ratchet\Wamp\WampServerInterface;

use JDare\ClankBundle\Server\App\Handler\RPCHandlerInterface;
use JDare\ClankBundle\Server\App\Handler\TopicHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use JDare\ClankBundle\Event\ClientEvent;
use JDare\ClankBundle\Event\ClientErrorEvent;

class ClankApp implements WampServerInterface {

    protected $topicHandler, $rpcHandler, $eventDispatcher;

    public function __construct(RPCHandlerInterface $rpcHandler, TopicHandlerInterface $topicHandler, EventDispatcherInterface $eventDispatcher)
    {
        $this->rpcHandler = $rpcHandler;
        $this->topicHandler = $topicHandler;
        $this->eventDispatcher = $eventDispatcher;
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

    public function onOpen(Conn $conn) {
        $event = new ClientEvent($conn, ClientEvent::$connected);
        $this->eventDispatcher->dispatch("clank.client.connected", $event);
    }

    public function onClose(Conn $conn) {
        $event = new ClientEvent($conn, ClientEvent::$disconnected);
        $this->eventDispatcher->dispatch("clank.client.disconnected", $event);
    }

    public function onError(Conn $conn, \Exception $e) {
        $event = new ClientErrorEvent($conn, ClientEvent::$error);

        $event->setException($e);
        $this->eventDispatcher->dispatch("clank.client.error", $event);
    }

}