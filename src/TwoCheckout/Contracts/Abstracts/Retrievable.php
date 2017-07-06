<?php
namespace TwoCheckout\Contracts\Abstracts;
use TwoCheckout\Exceptions\TwoCheckoutException;

trait Retrievable{

    public function retrieve(array $data){
        if(count($data) >= 1 && array_key_exists($data[0], $this->callables)){
            $params = (isset($data['params'])) ? $this->clearEmptyParams($data['params'])  : [];
            $uri = (isset($data['uri'])) ? $data['uri'] : $this->callables[$data[0]]['uri'];
            $method = $this->callables[$data[0]]['method'];
            return $this->client->request($uri, $params, $method);
        }
        throw new TwoCheckoutException("{$data[0]} is not callable through this {$this->resource_name} ", 1);
    }

    private function clearEmptyParams(array $params){
        $cleared = [];
        foreach($params as $key => $value){
            if(!empty($params[$key])){
                $cleared[$key] = $value;
            }
        }
        return $cleared;
    }
}