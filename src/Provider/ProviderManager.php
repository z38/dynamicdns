<?php

namespace Z38\DynamicDns\Provider;

class ProviderManager
{
    protected $provider;

    public function __construct()
    {
        $this->provider = array();
    }

    public static function fromDefaults()
    {
        $manager = new ProviderManager();

        $manager->add(new NoIp());

        return $manager;
    }

    public function add(ProviderInterface $provider)
    {
        $this->provider[$provider->getId()] = $provider;
    }

    public function get($id)
    {
        if (!isset($this->provider[$id])) {
            return;
        }

        return $this->provider[$id];
    }
}
