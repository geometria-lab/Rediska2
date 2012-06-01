<?php

namespace Rediska2\Client;

use Rediska2\Options;

class Factory
{
    static protected $classMap = array(
        'phpredis' => '\Rediska2\Client\Phpredis',
        'predis'   => '\Rediska2\Client\Predis'
    );

    /**
     * @static
     * @param Options\Redis $options
     * @throws \InvalidArgumentException
     * @return ClientInterface
     */
    static public function factory(Options\Redis $options)
    {
        $client = $options->get('client');

        if (is_string($client) && isset(static::$classMap[$client])) {
            $client = new static::$classMap[$client]($options);
        } else if (class_exists($client)) {
            $client = new $client($options);
        }

        if (!$client instanceof ClientInterface) {
            throw new \InvalidArgumentException('Invalid client');
        }

        return $client;
    }
}
