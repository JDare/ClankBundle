<?php

namespace JDare\ClankBundle\Server\App\Handler;

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

        $method = $this->toCamelCase($parts[1]);

        $result = null;
        if ($handler)
        {
            try{

                $result = call_user_func(array($handler, $method), $conn, $params);

            }catch(\Exception $e)
            {
                $conn->callError($id, $topic, $e->getMessage(),  array("code"=> $e->getCode(), "rpc" => $topic->getId(), "params" => $params));
                return;
            }

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

    /**
     * source: http://www.paulferrett.com/2009/php-camel-case-functions/
     *
     * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
     * @param    string   $str                     String in underscore format
     * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
     * @return   string                              $str translated into camel caps
     */
    private function toCamelCase($str, $capitalise_first_char = false) {
        if($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

}