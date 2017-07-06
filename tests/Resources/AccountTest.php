<?php
namespace TwoCheckout\Tests\Resources;

use PHPUnit\Framework\TestCase;
use TwoCheckout\Adapters\TwoCheckoutConfigAdapter;
use TwoCheckout\Adapters\TwoCheckoutHttpAdapter;
use TwoCheckout\Resources\Account;

class AccountTest extends TestCase
{
    public function setUp()
    {
        $this->adapter = \Mockery::mock(TwoCheckoutHttpAdapter::class)->makePartial();
    }

    /**
     * @expectedException TwoCheckout\Exceptions\TwoCheckoutException
     */
    public function testCreate()
    {
        $acc = new Account($this->adapter);
        $acc->create([]);
    }

    /**
     * @expectedException TwoCheckout\Exceptions\TwoCheckoutException
     */
    public function testUpdate()
    {
        $acc = new Account($this->adapter);
        $acc->update([]);
    }

     /**
     * @expectedException TwoCheckout\Exceptions\TwoCheckoutException
     */
    public function testDelete()
    {
        $acc = new Account($this->adapter);
        $acc->delete([]);
    }

    /**
     * @dataProvider companyInfoProvider
     */
    public function testGetCompanyInfo($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $acc = new Account($this->adapter);
        $info = $acc->getCompanyInfo();
        $this->assertNotNull($info);
        $this->assertEquals($info['vendor_name'], "Fake Company");
        $this->assertEquals($info['vendor_id'], "1234567");
    }

    public function companyInfoProvider()
    {
        return [[
            json_decode('{"response_code" : "OK", "response_message" : "company info retrieved", "vendor_company_info" : {"affiliate_url" : "http://www.fake-company.com", "currency_code" : "USD", "currency_name" : "US Dollars", "currency_symbol" : "$", "demo" : "P", "pending_return_url" : "http://www.fake-company.com", "return_method" : "1", "return_url" : "http://www.fake-company.com", "secret_word" : "wh1sky", "site_category" : "Electronics", "site_description" : "Fake company for testing!", "site_title" : "Fake Company", "soft_descriptor" : "Test", "url" : "www.fake-company.com", "vendor_id" : "1234567", "vendor_name" : "Fake Company"}}', true)
        ]];
    }

     /**
     * @dataProvider contactInfoProvider
     */
    public function testGetContactInfo($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $acc = new Account($this->adapter);
        $info = $acc->getContactInfo();
        $this->assertNotNull($info);
        $this->assertEquals($info['customer_service_email'], "support@fake-company.com");
    }

    public function contactInfoProvider()
    {
        return [[
            json_decode('{"response_code" : "OK", "response_message" : "contact info retrieved", "vendor_contact_info" : {"2co_account_level_id" : "3", "customer_service_email" : "support@fake-company.com", "customer_service_phone" : "555-555-5555", "customer_service_phone_ext" : "null", "mailing_address_1" : "​855 Grandview Avenue", "mailing_address_2" : "Suite 11", "mailing_address_id" : "2436561523", "mailing_city" : "Columbus", "mailing_country_code" : "USA", "mailing_postal_code" : "43215", "mailing_state" : "OH", "office_email" : "no-reply@fake-company.com", "office_phone" : "555-555-5555", "office_phone_ext" : null, "physical_address_1" : "​855 Grandview Avenue", "physical_address_2" : "Suite 11", "physical_address_id" : "2436561526", "physical_city" : "Columbus", "physical_country_code" : "USA", "physical_postal_code" : "43215", "physical_state" : "OH", "vendor_id" : "123456"}}', true)
        ]];
    }
}