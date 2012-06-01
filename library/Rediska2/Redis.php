<?php

namespace Rediska2;

class Redis
{
    /**
     * Options
     *
     * @var Options\Redis
     */
    protected $options;

    /**
     * @var Client\ClientInterface
     */
    protected $client;

    /**
     * Constructor
     *
     * @param array|Options\Redis $options Redis options
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Set options
     *
     * @param array|Options\Redis $options
     * @return Redis
     */
    public function setOptions($options)
    {
        if ($options instanceof Options\Redis) {
            $this->options = $options;
        } else {
            $this->options = new Options\Redis($options);
        }

        $client = Client\Factory::factory($this->options);

        $this->setClient($client);

        return $this;
    }

    /**
     * Get options
     *
     * @return Options\Redis
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set client
     *
     * @param Client\ClientInterface $client
     */
    public function setClient(Client\ClientInterface $client)
    {
        if ($this->client !== null) {
            $this->client->reset();
            $this->client = null;
        }

        $this->client = $client;
    }

    /**
     * Get client
     *
     * @return Client\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }
}
