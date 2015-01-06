<?php

namespace Z38\DynamicDns\Provider;

use Buzz\Listener\BasicAuthListener;
use Buzz\Listener\ListenerChain;
use Z38\DynamicDns\BrowserFactory;
use Z38\DynamicDns\Exception\InvalidHostException;
use Z38\DynamicDns\Exception\UpdateException;

class NoIp implements ProviderInterface
{
    protected $browser;

    public function __construct()
    {
        $this->browser = BrowserFactory::create();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'noip';
    }

    /**
     * {@inheritdoc}
     */
    public function validate($host, $data)
    {
        if (!isset($data['username']) || !isset($data['password'])) {
            throw new InvalidHostException('Please set "username" and "password".', $host);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update($host, $ip, $data)
    {
        $url = sprintf('https://dynupdate.no-ip.com/nic/update?hostname=%s', $host);
        if ($ip !== null) {
            $url .= '&myip='.$ip;
        }
        $headers = array(
            'User-Agent' => 'ddns-updater/v0.1.1 z38@users.noreply.github.com',
        );

        $this->browser->setListener(new BasicAuthListener($data['username'], $data['password']));
        $response = $this->browser->get($url, $headers);
        $this->browser->setListener(new ListenerChain());
        $responseContent = $response->getContent();

        if (strpos($responseContent, 'good') !== 0 && strpos($responseContent, 'nochg') !== 0) {
            throw new UpdateException(sprintf('Updated failed (%s).', substr($responseContent, 0, 200)), $host);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isIpRequired()
    {
        return false;
    }
}
