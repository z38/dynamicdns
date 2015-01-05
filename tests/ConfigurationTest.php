<?php

namespace Z38\DynamicDns\Tests;

use Z38\DynamicDns\Configuration;

/**
 * @covers \Z38\DynamicDns\Configuration
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadSample()
    {
        $config = Configuration::loadFromFile(__DIR__.'/../ddns-updater.yml.dist');
        $this->assertCount(1, $config->getAllHosts());
    }

    public function testLoadEmpty()
    {
        $config = Configuration::loadFromFile(__DIR__.'/config-empty.yml');
        $this->assertCount(0, $config->getAllHosts());
    }

    public function testLoadComplex()
    {
        $config = Configuration::loadFromFile(__DIR__.'/config-complex.yml');
        $this->assertCount(2, $config->getAllHosts());

        $data = $config->getHostData('first.com');
        $this->assertSame('firstuser', $data['username']);
        $this->assertSame('bar', $data['foo']);
    }

    /**
     * @expectedException \Z38\DynamicDns\Exception\InvalidHostException
     */
    public function testLoadWithoutProvider()
    {
        $config = Configuration::loadFromFile(__DIR__.'/config-noprovider.yml');
    }
}
