<?php
namespace TwoCheckout\Contracts;

interface TwoCheckoutHttpInterface{
    public function request($urlSuffix, array $data, $method);
}