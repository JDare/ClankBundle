<?php

namespace JDare\ClankBundle\RPC;

class SampleService
{
    public function add(array $params)
    {

        return array("result" => array_sum($params));
    }
}