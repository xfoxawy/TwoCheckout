<?php

namespace TwoCheckout\Contracts\Abstracts;

use TwoCheckout\Contracts\TwoCheckoutResourceInterface;
use TwoCheckout\Contracts\TwoCheckoutHttpInterface;
use TwoCheckout\Exceptions\TwoCheckoutException;
use TwoCheckout\Contracts\Abstracts\Retrievable;

abstract class ImmutableResource implements TwoCheckoutResourceInterface
{
    use Retrievable;
    
    protected $client;
    private $resource_name;
    
    function __construct(TwoCheckoutHttpInterface $client)
    {
        $this->client = $client;
        $this->resource_name = get_class($this);
    }

    public function create(array $params=null){
        throw new TwoCheckoutException("Create Operation Not Allowed, {$this->resource_name} is Immutable Resource", 1);
    }

    public function update($id=null, array $params=null){
        throw new TwoCheckoutException("Update Operation Not Allowed, {$this->resource_name} is Immutable Resource", 1);
    }

    public function delete($id=null){
        throw new TwoCheckoutException("Delete Operation Not Allowed, {$this->resource_name} is Immutable Resource", 1);
    }

    
}