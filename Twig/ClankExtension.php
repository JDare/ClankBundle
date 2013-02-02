<?php

namespace JDare\ClankBundle\Twig;

class ClankExtension extends \Twig_Extension
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return "clank";
    }

    public function getFunctions()
    {
        return array(
            "clank_client" => new \Twig_Function_Method($this, 'clientOutput', array('is_safe' => array('html')))
        );
    }

    public function clientOutput()
    {
        return $this->container->get("templating")->render("JDareClankBundle::client.html.twig");
    }
}