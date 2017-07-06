<?php

namespace TwoCheckout\Contracts\Abstracts;

use TwoCheckout\Contracts\TwoCheckoutResourceInterface;
use TwoCheckout\Contracts\TwoCheckoutHttpInterface;
use TwoCheckout\Contracts\Abstracts\Retrievable;

abstract class FullResource implements TwoCheckoutResourceInterface
{
    use Retrievable;
    
    protected $client;
    private $resource_name;
    
    function __construct(TwoCheckoutHttpInterface $client)
    {
        $this->client = $client;
        $this->resource_name = get_class($this);
    }

    /**
     * \TwoCheckout\Adapters\TwoCheckoutConfigAdapter
     * Access to config array
     * @return Object 
     */
    public function config()
    {
        return $this->client->config;
    }

    abstract public function get($id);
    
    abstract public function create(array $params);

    abstract public function update($id, array $params);

    abstract public function delete($id);

   
}