<?php

namespace Z38\DynamicDns\Exception;

class UpdateException extends \RuntimeException implements ExceptionInterface
{
    public function __construct($message, $host)
    {
        if ($message instanceof \Exception) {
            $message = $message->getMessage();
        }
        if (!$message) {
            $message = 'Could not update.';
        }

        parent::__construct(sprintf('%s: %s', $host, $message));
    }
}
