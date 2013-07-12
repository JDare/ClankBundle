<?php
namespace JDare\ClankBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use \React\EventLoop\LoopInterface;

class ServerEvent extends Event
{
    protected $loop;

    public function __construct(LoopInterface $loop) {
        $this->loop = $loop;
    }

    /**
     * Get Server Event Loop to add other services in the same loop.
     */
    public function getEventLoop() {
        return $this->loop;
    }
}