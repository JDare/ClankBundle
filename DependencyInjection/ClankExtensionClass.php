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

    }

    private function setupWebSocketServer($config)
    {
        $port = 8080;
        if (isset($config['port']) && $config['port'])
        {
            $port = (int)$config['port'];
        }

        $this->container->setParameter('jdare_clank.web_socket_server.port', $port);

        if (isset($config['host']) && $config['host'])
            $this->container->setParameter('jdare_clank.web_socket_server.host', $config['host']);
    }

    public function getAlias()
    {
        return "clank";
    }
}
