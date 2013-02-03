<?php

namespace JDare\ClankBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ClankExtensionClass extends Extension
{
    /**
     * @var ContainerBuilder
     */
    private $container;
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $config = array();
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }

        if (isset($config['web_socket_server']) && $config['web_socket_server'])
        {
            $this->setupWebSocketServer($config['web_socket_server']);
        }

        if (isset($config['rpc']) && $config['rpc'])
        {
            $this->setupRPCServices($config['rpc']);
        }

        if (isset($config['topic']) && $config['topic'])
        {
            $this->setupTopicServices($config['topic']);
        }

        if (isset($config['periodic']) && $config['periodic'])
        {
            $this->setupPeriodicServices($config['periodic']);
        }

        if (isset($config['session_handler']) && $config['session_handler'])
        {
            $this->setupSessionHandler($config['session_handler']);
        }

    }

    private function setupWebSocketServer($config)
    {
        if (isset($config['port']) && $config['port'])
        {
            $port = (int)$config['port'];
        }

        $this->container->setParameter('jdare_clank.web_socket_server.port', $port);

        if (isset($config['host']) && $config['host'])
            $this->container->setParameter('jdare_clank.web_socket_server.host', $config['host']);
    }

    private function setupSessionHandler($config)
    {
        if ($config)
        {
            $this->container->setParameter('jdare_clank.session_handler', $config);
        }
    }

    private function setupRPCServices($config)
    {
        $this->container->setParameter('jdare_clank.rpc_services', $config);
    }

    private function setupTopicServices($config)
    {
        $this->container->setParameter('jdare_clank.topic_services', $config);
    }

    private function setupPeriodicServices($config)
    {
        $this->container->setParameter('jdare_clank.periodic_services', $config);
    }

    public function getAlias()
    {
        return "clank";
    }
}
