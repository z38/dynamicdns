<?php

namespace Z38\DynamicDns;

use Buzz\Browser;
use Buzz\Client\Curl;

class BrowserFactory
{
    /**
     * Create a Buzz\Brower instance
     *
     * @return \Buzz\Browser
     */
    public static function create()
    {
        return new Browser(new Curl());
    }
}
