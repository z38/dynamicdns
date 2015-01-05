<?php

namespace Z38\DynamicDns\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Z38\DynamicDns\Configuration;

class Command extends BaseCommand
{
    protected function addConfigOption($name, $shortcut)
    {
        return $this->addOption(
           $name,
           $shortcut,
           InputOption::VALUE_REQUIRED,
           'If set, the specified configuration file will be used',
           'ddns-updater.yml'
        );
    }

    protected function loadConfig($file)
    {
        if (!is_readable($file)) {
            throw new \RuntimeException(sprintf('Can not read configuration file "%s".', $file));
        }

        return Configuration::loadFromFile($file);
    }
}
