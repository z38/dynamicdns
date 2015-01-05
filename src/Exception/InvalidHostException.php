<?php

namespace Z38\DynamicDns\Exception;

class InvalidHostException extends \InvalidArgumentException implements ExceptionInterface
{
    public function __construct($message, $host)
    {
        parent::__construct(sprintf('%s: %s', $host, $message));
    }
}
