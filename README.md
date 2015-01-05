DynamicDns
==========

[![Build Status](https://travis-ci.org/z38/dynamicdns.svg?branch=master)](https://travis-ci.org/z38/dynamicdns)

**DynamicDns** contains dynamic DNS updater utility (called *ddns-updater*). In addition, it allows users to write their custom Dynamic DNS updater by providing an easy-to-use API.

```php
<?php

require __DIR__.'/vendor/autoload.php';

$host = 'yourhostname.ddns.net';
$ip = '173.194.66.100';
$credentials = array(
    'username' => 'foo',
    'password' => 'bar'
);

$provider = new Z38\DynamicDns\Provider\NoIp();
$provider->update($host, $ip, $credentials);
```

ddns-updater
------------

Simply invoke `./bin/ddns-updater` to get a list of the commands available.

License
-------

MIT
