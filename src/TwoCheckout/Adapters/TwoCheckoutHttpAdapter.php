<?php
namespace TwoCheckout\Adapters;

use GuzzleHttp\Client as HttpClient;
use TwoCheckout\Adapters\TwoCheckoutConfigAdapter as Config;
use TwoCheckout\Exceptions\TwoCheckoutApiException;
use TwoCheckout\Contracts\TwoCheckoutHttpInterface;

class TwoCheckoutHttpAdapter implements TwoCheckoutHttpInterface
{
    /**
     *  Http Client 
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * API Config 
     * @var \TwoCheckout\Adapters\TwoCheckoutConfigAdapter
     */
    public $config;

    public function __construct(Config $config, HttpClient $client)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * Process Http Request
     * @param  String $urlSuffix
     * @param  Array  $data     
     * @param  String $method   
     * @return HttpResponse
     */
    public function request($urlSuffix, array $data=[], $method="POST"){
        if($method == "POST"){
            $response = $this->post($urlSuffix, $data);
        }else if($method == "GET"){
            $response = $this->get($urlSuffix, $data);
        }
        return $this->processResponse($response);
    }

    /**
     * Process Http Get Request
     * @param  String $urlSuffix
     * @param  Array  $data     
     * @return HttpResponse
     * @throws TwoCheckout\Exceptions\TwoCheckoutApiException
     */
    public function get($urlSuffix, array $data=[]){
        return $this->client->request('GET', $this->config->base_uri . $urlSuffix,
        [
            'query' => $data,
            'auth'=>[
                $this->config->username,
                $this->config->password
            ],
            'headers' => [
                'Accept'=> 'application/json',
                'Content-Type' => 'application/json'

            ],
            'verify' => $this->config->verify_ssl,
            'http_errors' => false
        ]);
    }
    
    /**
     * Process Http Post Request
     * @param  String $urlSuffix
     * @param  Array  $data     
     * @return HttpResponse     
     * @throws TwoCheckout\Exceptions\TwoCheckoutApiException
     */
    public function post($urlSuffix, array $data=[]){
        return $this->client->request('POST', $this->config->base_uri . $urlSuffix,
        [
            'query'=> $data,
            'auth'=>[
                $this->config->username,
                $this->config->password
            ],
            'headers' => [
                'Accept'=> 'application/json',
                'Content-Type' => 'application/json'

            ],
            'verify' => $this->config->verify_ssl,
            'http_errors' => false,
            'json' =>  $data
        ]);
    }

    private function processResponse($http_response){
        $this->checkErrors($http_response);
        $content = $http_response->getBody()->getContents();
        return json_decode($content, true);
    }

    private function checkErrors($http_response){
        $code = $http_response->getStatusCode();
        $msg = "Status {$code}. Unknown API Request Failure";
        if($code >= 300){
            $response = json_decode($http_response->getBody()->getContents(), true);
            if(isset($response['errors'])){
                $msg = $response['errors'][0]['code'] . ' : ' .$response['errors'][0]['message'];
            }
            
            if(isset($response['exception']) && !empty($response['exception'])){
                $msg = $response['exception']['errorCode'] .' : ' . $response['exception']['errorMsg'];
            }
            throw new TwoCheckoutApiException($msg);
        }
    }
}