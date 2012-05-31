<?php

namespace Rediska2\Options;

class Redis extends AbstractOptions
{
    protected $options = array(
        'servers'    => array(),
        'serializer' => array(),
    );
}
