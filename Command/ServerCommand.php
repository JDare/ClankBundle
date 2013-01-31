<?php

namespace Jez433\ClankBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('clank:server')
            ->setDescription('Starts the Clank Servers')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $main = $this->getContainer()->get("jez433_clank.entry_point");

        $output->writeln("Launching Ratchet Server...");

        $main->launch();

    }
}