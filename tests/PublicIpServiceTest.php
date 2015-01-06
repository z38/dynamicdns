<?php

namespace Z38\DynamicDns\Tests;

use Z38\DynamicDns\PublicIpService;

/**
 * @covers \Z38\DynamicDns\PublicIpService
 */
class PublicIpServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $ipExpected = trim(file_get_contents('http://checkip.amazonaws.com/'));

        $service = new PublicIpService();
        $ip = $service->get();

        $this->assertSame($ipExpected, $ip);
    }
}
