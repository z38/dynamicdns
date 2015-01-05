<?php

namespace Z38\DynamicDns\Provider;

interface ProviderInterface
{
    /**
     * Return a alphanumeric, lowercase ID
     *
     * @return string
     */
    public function getId();

    /**
     * Validate a host entry
     *
     * @param string $host Hostname
     * @param string $data Extra parameters
     *
     * @throws Z38\DynamicDns\InvalidHostException
     */
    public function validate($host, $data);

    /**
     * Update a host entry
     *
     * @param string $host Hostname
     * @param string $ip   New IP
     * @param array  $data Extra parameters
     *
     * @throws Z38\DynamicDns\UpdateException
     */
    public function update($host, $ip, $data);

    /**
     * Checks if the server can determine the IP by itself.
     *
     * @return bool
     */
    public function isIpRequired();
}
