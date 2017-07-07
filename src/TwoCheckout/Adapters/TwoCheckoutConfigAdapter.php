<?php
namespace TwoCheckout\Adapters;

use TwoCheckout\Exceptions\TwoCheckoutException; 

class TwoCheckoutConfigAdapter 
{
    const API_URL = "www.2checkout.com";
    const SANDBOX_URL = "sandbox.2checkout.com";
    
    private $config;
    

    function __construct(array $config)
    {
        $this->setConfig($config);
    }

    public function setConfig(array $config){
        $this->config = $config;
        $this->verifySSLCert();
        $this->setBaseUrl();
    }

    public function setBaseUrl(){
        $base_url = self::API_URL;
        $http = 'https://';

        if($this->env == 'sandbox'){
            $base_url = self::SANDBOX_URL;
        }
        if($this->env == 'sandbox' && !$this->verify_ssl){
            $http = 'http://';
        }

        $this->base_uri = $http.$base_url;
    }

    public function verifySSLCert(){
        if($this->env == 'production'){
            $this->config['verify_ssl'] = true;
        }

        if($this->ssl_cert_path != 'default'){
            if(!is_file($this->ssl_cert_path)){
                throw new TwoCheckoutException("SSL Cert Path is not accessible or invalid", TwoCheckoutException::INVALID_SSL_CERT);
            }
            $this->verify_ssl = $this->ssl_cert_path;
        }
    }

    public function __get($var){
        if(isset($this->config[$var])){
            return $this->config[$var];
        }
        return null;
    }

    public function __set($var, $val){
        $this->config[$var] = $val;
    }

}