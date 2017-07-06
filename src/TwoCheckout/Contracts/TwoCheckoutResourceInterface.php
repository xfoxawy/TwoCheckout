<?php
namespace TwoCheckout\Contracts;

interface TwoCheckoutResourceInterface{
    public function __construct(TwoCheckoutHttpInterface $client);
    public function retrieve(array $params);
    public function create(array $params);
    public function update($id, array $params);
    public function delete($id);
}