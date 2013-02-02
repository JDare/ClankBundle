<?php

namespace JDare\ClankBundle\Topic;

use JDare\ClankBundle\Topic\TopicInterface;

use Ratchet\ConnectionInterface as Conn;

class AcmeTopic implements TopicInterface
{

    /**
     * This will receive any Subscription requests for this exact topic.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @return mixed|void
     */
    public function onSubscribe(Conn $conn, $topic)
    {
        $topic->broadcast($conn->resourceId . " has joined " . $topic->getId());
    }

    /**
     * This will receive any UnSubscription requests for this exact topic.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @return mixed|void
     */
    public function onUnSubscribe(Conn $conn, $topic)
    {
        $topic->broadcast($conn->resourceId . " has left " . $topic->getId());
    }


    /**
     * This will receive any Publish requests for this exact topic.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @param $event
     * @param array $exclude
     * @param array $eligible
     * @return mixed|void
     */
    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible)
    {
        /*
         * $topic contains the FULL uri, so if anything extra was added to that, you can find out the full path based on that.
         */

        $topic->broadcast(array(
            "sender" => $conn->resourceId,
            "topic" => $topic->getId(),
            "event" => $event
        ));
    }

}