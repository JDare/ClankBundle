<?php

namespace Jez433\ClankBundle\Server\Type;

interface ServerTypeInterface
{
    public function __construct($host, $port);
    public function launch();
}