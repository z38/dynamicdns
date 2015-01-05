<?php

namespace Z38\DynamicDns\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Z38\DynamicDns\Configuration;
use Z38\DynamicDns\PublicIpService;

class UpdateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('update')
            ->setDescription('Update a DDNS entry')
            ->addArgument(
                'hosts',
                InputArgument::IS_ARRAY,
                'Which entries do you want to update?'
            )
            ->addConfigOption('config', 'c')
            ->addOption(
               'refresh',
               'r',
               InputOption::VALUE_NONE,
               'If set, the IP will be refreshed'
            )
            ->addOption(
               'ip',
               null,
               InputOption::VALUE_REQUIRED,
               'If set, this IP will be used to update the entry'
            )
            ->addOption(
               'all',
               'a',
               InputOption::VALUE_NONE,
               'If set, all hosts will be updated'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->loadConfig($input->getOption('config'));

        $hosts = $input->getArgument('hosts');
        $refresh = $input->getOption('refresh');
        $ip = $input->getOption('ip');
        $all = $input->getOption('all');

        if ($refresh && $ip) {
            throw new \RuntimeException('--refresh can not be used in conjunction with --ip.');
        }

        if ($all) {
            if (count($hosts)) {
                throw new \RuntimeException('--all can not be used in conjunction with a list of hosts.');
            }
            $hosts = $config->getAllHosts();
        }

        if ($ip === 'public') {
            $output->write('Determine public IP ... ');
            $service = new PublicIpService();
            $ip = $service->get();
            $output->writeln('<info>OK</info>');
        }

        if (!$ip && !$refresh && $config->isIpRequired($hosts)) {
            throw new \RuntimeException('Unable to determine IP (use --ip).');
        }

        foreach ($hosts as $host) {
            if ($refresh) {
                $ip = $this->resolve($host.'.');
            }
            $output->write(sprintf('Update %s ... ', $host));
            $this->updateHost($config, $host, $ip);
            $output->writeln('<info>OK</info>');
        }
    }

    protected function updateHost(Configuration $config, $host, $ip)
    {
        $provider = $config->getHostProvider($host);
        $data = $config->getHostData($host);
        $provider->update($host, $ip, $data);
    }

    protected function resolve($hostname)
    {
        $ip = gethostbyname($hostname);
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \RuntimeException(sprintf('Could not resolve "%s".', $hostname));
        }

        return $ip;
    }
}
