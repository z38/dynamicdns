<?php

namespace Z38\DynamicDns\Tests;

use Z38\DynamicDns\BrowserFactory;

/**
 * @covers \Z38\DynamicDns\BrowserFactory
 */
class BrowserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $browser = BrowserFactory::create();
        $this->assertInstanceOf('\Buzz\Browser', $browser);
    }
}
