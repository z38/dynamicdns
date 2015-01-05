<?php

namespace Z38\DynamicDns;

use Buzz\Browser;

class PublicIpService
{
    protected $browser;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->browser = new Browser();
    }

    /**
     * Return the current public IP
     *
     * @return string
     */
    public function get()
    {
        $response = $this->browser->get('http://api.ipify.org/');

        return trim($response->getContent());
    }
}
