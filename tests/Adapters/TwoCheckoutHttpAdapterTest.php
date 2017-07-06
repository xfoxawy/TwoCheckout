<?php
namespace TwoCheckout\Tests;

use PHPUnit\Framework\TestCase;
// use Mockery\Mockery;
use TwoCheckout\Adapters\TwoCheckoutHttpAdapter;
use TwoCheckout\Adapters\TwoCheckoutConfigAdapter;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class TwoCheckoutHttpAdapterTest extends TestCase
{
    public function setUp()
    {
        /**
         * Mocking Config Props
         */
        $this->config = \Mockery::mock(TwoCheckoutConfigAdapter::class);
        $this->config->username = 'usertest';
        $this->config->password = 'passtest';
        $this->config->base_uri = 'https://sandbox.2checkout.com';
        $this->config->verify_ssl = true;

    }

    public function testSuccessfulRequest()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type'=>'application/json'], json_encode(['message'=>'OK'])),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $adapter = new TwoCheckoutHttpAdapter($this->config, $client);
        $response = $adapter->request('/api/foo',[],'GET');
        $this->assertNotNull($response);
        $this->assertEquals($response['message'], 'OK');
    }

    /**
     * @expectedException TwoCheckout\Exceptions\TwoCheckoutApiException
     */
    public function testFailedRequest()
    {
        /**
         * Mocking HttpClient Requests
         */
        $mock = new MockHandler([
            new Response(400, ['Content-Type'=>'application/json'], json_encode(['errors'=>[['code'=>'PARAM_MISSING', 'message'=>'Foo is Required']]]))
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $adapter = new TwoCheckoutHttpAdapter($this->config, $client);
        $response = $adapter->request('/api/bar',['x'=>'y'],'POST');
    }
}