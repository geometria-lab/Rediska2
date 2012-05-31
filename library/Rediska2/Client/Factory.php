<?php

namespace Rediska2\Client;

use Rediska2\Options;

class Factory
{
    static public function factory(Options\Redis $options)
    {
        $options->get('client');
    }
}
