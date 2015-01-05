<?php

namespace Z38\DynamicDns\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Z38\DynamicDns\PublicIpService;

class IpCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ip')
            ->setDescription('Print your current public IP')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = new PublicIpService();
        $output->writeln($service->get());
    }
}
