<?php

namespace JDare\ClankBundle\Server\App\Handler;

use Ratchet\ConnectionInterface as Conn;
use Ratchet\Wamp\Topic;


class TopicHandler implements TopicHandlerInterface
{
    protected $topicServices, $container;

    public function setTopicServices($topicServices)
    {
        $this->topicServices = $topicServices;
    }

    public function getTopicServices()
    {
        return $this->topicServices;
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function onSubscribe(Conn $conn, $topic)
    {
        //if topic service exists, notify it
        $this->dispatch(__METHOD__, $conn, $topic);
    }

    public function onUnSubscribe(Conn $conn, $topic)
    {
        //if topic service exists, notify it
        $this->dispatch(__METHOD__, $conn, $topic);
    }

    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible)
    {
        if (!$this->dispatch(__METHOD__, $conn, $topic, $event, $exclude, $eligible))
        {
            //default behaviour is to broadcast to all.
            $topic->broadcast($event);
            return;
        }
    }

    public function dispatch($event, Conn $conn, Topic $topic, $payload = null, $exclude = null, $eligible = null)
    {
        $event = explode(":", $event);
        if (count($event)<=0)
        {
            return false;
        }
        $event = $event[count($event)-1];
        //if topic service exists, notify it
        $handler = $this->getTopicHandler($topic);
        if ($handler)
        {
            if ($payload) //its a publish call.
            {
                call_user_func(array($handler, $event), $conn, $topic, $payload, $exclude, $eligible);
            }else{
                call_user_func(array($handler, $event), $conn, $topic);
            }

            return true;
        }
        return false;
    }

    public function getTopicHandler(Topic $topic)
    {
        //get network namespace to see if its valid
        $parts = explode("/", $topic->getId());
        if ($parts <= 0)
        {
            return false;
        }

        $serviceMatch = $parts[0];



        foreach($this->getTopicServices() as $topicService)
        {
            if ($topicService['name'] === $serviceMatch)
                return $this->getContainer()->get($topicService['service']);
        }
        return false;
    }

}