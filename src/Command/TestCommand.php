<?php

namespace Z38\DynamicDns\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('test')
            ->setDescription('Test configuration')
            ->addConfigOption('config', 'c')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->loadConfig($input->getOption('config'));

        $output->writeln('<info>The configuration seems valid.</info>');
    }
}
