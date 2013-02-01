<?php

namespace Jez433\ClankBundle\Server\App\Handler;

use Ratchet\ConnectionInterface as Conn;

class RPCHandler implements RPCHandlerInterface
{
    protected $rpcServices, $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function dispatch(Conn $conn, $id, $topic, array $params)
    {
        $parts = explode("/", $topic->getId());

        if (count($parts) < 2)
        {
            $conn->callError($id, $topic, "Incorrectly formatted Topic name",  array("topic_name" => $topic->getId()));
            return;
        }

        $handler = $this->getHandler($parts[0]);

        $result = null;
        if ($handler)
        {
            $result = call_user_func(array($handler, $parts[1]), $params);
            if ($result === null) //incase handler doesnt return anything!
                $result = false;
        }

        if ($result)
        {
            if (!is_array($result))
                $result = array($result);

            $conn->callResult($id, $result);
            return;

        }elseif ($result === false)
        {
            $conn->callError($id, $topic, "RPC Failed",  array("rpc" => $topic->getId(), "params" => $params));
        }

        $conn->callError($id, $topic, "Unable to find that command",  array("rpc" => $topic->getId(), "params" => $params));
        return;


    }


    private function getHandler($name)
    {
        foreach($this->rpcServices as $service)
        {
            if ($service['name'] === $name)
                return $this->getContainer()->get($service['service']);
        }
        return false;
    }

    public function setRPCServices($rpcServices)
    {
        $this->rpcServices = $rpcServices;
    }


}