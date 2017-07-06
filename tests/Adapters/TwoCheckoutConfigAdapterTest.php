<?php
namespace TwoCheckout\Tests\Adapters;
use PHPUnit\Framework\TestCase;
use TwoCheckout\Adapters\TwoCheckoutConfigAdapter;

class TwoCheckoutConfigAdapterTest extends TestCase
{
    /**
     * @dataProvider configArrayProvider
     */
    public function testConfigInit($config)
    {
        $adapter = new TwoCheckoutConfigAdapter($config);
        $this->assertNotNull($adapter);
        $this->assertNotNull($adapter->env);
        $this->assertNotNull($adapter->seller_id);
        $this->assertNull($adapter->invalid);
        $this->assertEquals("https://sandbox.2checkout.com", $adapter->base_uri);
    }

    public function configArrayProvider()
    {
        return [[[
            'env' => 'development',
            'seller_id' => '01010101010', // Alias:Account Number
            'username' => 'test',
            'password' => 'test',
            'publishable_key' => '75B8E0EE-579O-D6CF-B331-O73053F193FP',
            'private_key' => '62D9D114-0C6E-4318-B627-F7F4317ED1CF',
            'secret_word' => 'fsdfgsdgiqjHoihjwie800ZjU0LThiOTgtMWRiZWMxMmJjM2Ji',
            'default_currency'=>'USD',
            'sandbox' => true,
            'verify_ssl' => true,
            'ssl_cert_path' => 'default',  //production only
            'api_version' => '0.3.1',
        ]]];
    }
    
    /**
     * @expectedException TwoCheckout\Exceptions\TwoCheckoutException
     * @expectedExceptionCode 101 
     */
    public function testInvalidSSLCertPath()
    {
        new TwoCheckoutConfigAdapter(['ssl_cert_path' => '/dev/null']);
    }
}