<?php

namespace Jez433\ClankBundle\Service;

class SampleService implements PeriodicInterface
{
    public function tick()
    {
        echo "Executed once every 5 seconds" . PHP_EOL;
    }
}
