<?php

namespace JDare\ClankBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use JDare\ClankBundle\DependencyInjection\ClankExtensionClass;

class JDareClankBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        // register extensions that do not follow the conventions manually
        $container->registerExtension(new ClankExtensionClass());

        parent::build($container);


    }
}
