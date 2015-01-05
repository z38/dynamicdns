<?php

namespace Z38\DynamicDns;

use Symfony\Component\Yaml\Yaml;
use Z38\DynamicDns\Provider\ProviderManager;
use Z38\DynamicDns\Exception\InvalidHostException;

class Configuration
{
    protected $providerManager;
    protected $hostData;
    protected $hostProvider;

    protected function __construct(ProviderManager $providerManager)
    {
        $this->providerManager = $providerManager;
        $this->hostData = array();
        $this->hostProvider = array();
    }

    public static function loadFromFile($file)
    {
        $yaml = Yaml::parse(file_get_contents($file));
        $configuration = new Configuration(ProviderManager::fromDefaults());

        if (isset($yaml['parameters'])) {
            $parameters = $yaml['parameters'];
        } else {
            $parameters = array();
        }

        if (isset($yaml['hosts'])) {
            foreach ($yaml['hosts'] as $host => $hostData) {
                if (!isset($hostData['provider'])) {
                    throw new InvalidHostException('No provider key set.', $host);
                }
                $providerId = $hostData['provider'];
                unset($hostData['provider']);
                $hostData = self::replacePlaceholders($hostData, $parameters);
                $configuration->addHost($host, $providerId, $hostData);
            }
        }

        return $configuration;
    }

    public function addHost($host, $providerId, $hostData = array())
    {
        if (($provider = $this->providerManager->get($providerId)) === null) {
            throw new InvalidHostException(sprintf('Unknown provider "%s".', $providerId), $host);
        }
        $provider->validate($host, $hostData);

        $this->hostData[$host] = $hostData;
        $this->hostProvider[$host] = $provider;
    }

    public function getAllHosts()
    {
        return array_keys($this->hostProvider);
    }

    public function getHostProvider($host)
    {
        return $this->hostProvider[$host];
    }

    public function getHostData($host)
    {
        return $this->hostData[$host];
    }

    public function isIpRequired($hosts)
    {
        if (!is_array($hosts)) {
            $hosts = array($hosts);
        }

        foreach ($hosts as $host) {
            if ($this->getHostProvider($host)->isIpRequired()) {
                return true;
            }
        }

        return false;
    }

    protected static function replacePlaceholders($data, $parameters)
    {
        $search = array_map(function ($key) { return '%'.$key.'%'; }, array_keys($parameters));
        $replacements = array_values($parameters);

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->replacePlaceholders($data[$key], $parameters);
            } elseif (is_string($value)) {
                $data[$key] = str_replace($search, $replacements, $data[$key]);
            }
        }

        return $data;
    }
}
