<?php
namespace TwoCheckout\Tests\Resources;

use PHPUnit\Framework\TestCase;
use TwoCheckout\Adapters\TwoCheckoutConfigAdapter;
use TwoCheckout\Adapters\TwoCheckoutHttpAdapter;
use TwoCheckout\Resources\Option;

class OptionTest extends TestCase
{
    public function setUp()
    {
        $this->adapter = \Mockery::mock(TwoCheckoutHttpAdapter::class)->makePartial();
        $this->adapter->config = new \StdClass();
        $this->adapter->config->base_uri = 'https://www.2checkout.com/';
    }

    /**
     * @dataProvider listOptionsProvider
     */
    public function testListOptions($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $option = new Option($this->adapter);
        $list = $option->list();
        $this->assertNotNull($list);
        $this->assertEquals('4688550010', $list[0]['option_id']);
    }

    public function listOptionsProvider()
    {
        return [[json_decode('{"options" : [{"option_id" : "4688550010", "option_name" : "Volume", "option_values" : [{"option_value_id" : "4688550013", "option_value_name" : "Low", "option_value_surcharge" : "1.00"}, {"option_value_id" : "4688550016", "option_value_name" : "Medium", "option_value_surcharge" : "2.00"}, {"option_value_id" : "4688550019", "option_value_name" : "High", "option_value_surcharge" : "3.00"} ] } ], "page_info" : {"cur_page" : "1", "first_entry" : 1, "first_page" : 1, "first_page_url" : "https://www.2checkout.com/api/products/list_options?cur_page=1", "last_entry" : "1", "last_page" : 1, "last_page_url" : "https://www.2checkout.com/api/products/list_options?cur_page=1", "next_page" : null, "pagesize" : "20", "previous_page" : null, "total_entries" : "1"}, "response_code" : "OK", "response_message" : "Option information retrieved successfully."}', true)]];
    }

    /**
     * @dataProvider createOptionProvider
     */
    public function testCreateOption($data)
    {  
        $this->adapter->shouldReceive('request')->andReturn($data);
        $option = new Option($this->adapter);
        $res = $option->new('testOpt','testVal',10);
        $this->assertNotNull($res);
        $this->assertEquals($res, '1234567890');
    }

    public function createOptionProvider()
    {
        return [[json_decode('{"option_id" : "1234567890", "response_code" : "OK", "response_message" : "Option created successfully"}', true)]];
    }

    /**
     * @dataProvider getOptionProvider
     */
    public function testGetOption($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $option = new Option($this->adapter);
        $res = $option->get('4688355343');
        $this->assertNotNull($res);
        $this->assertEquals($res['option_name'], 'decibels');
    }

    public function getOptionProvider()
    {
        return  [[json_decode('{"option" : [{"option_id" : "4688355343", "option_name" : "decibels", "option_values" : [{"option_value_id" : "4688355346", "option_value_name" : "150dB", "option_value_surcharge" : "11.00"}, {"option_value_id" : "4688441332", "option_value_name" : "200dB", "option_value_surcharge" : "22.00"}, {"option_value_id" : "4688441335", "option_value_name" : "250dB", "option_value_surcharge" : "33.00"} ] } ], "response_code" : "OK", "response_message" : "Option detail retrieved successfully."}', true)]];
    }

    /**
     * @dataProvider updateOptionProvider
     */
    public function testUpdateOption($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $option = new Option($this->adapter);
        $res = $option->update('4688355343', ['option_value_name'=>'new test']);
        $this->assertNotNull($res);
        $this->assertTrue($res);
    }

    public function updateOptionProvider()
    {
        return [[json_decode('{"response_code" : "OK", "response_message" : "Option updated successfully"}', true)]];
    }

    /**
     * @dataProvider deleteOptionProvider
     */
    public function testDeleteOption($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $option = new Option($this->adapter);
        $res = $option->delete('4688355343');
        $this->assertNotNull($res);
        $this->assertTrue($res);
    }

    public function deleteOptionProvider()
    {
        return [[json_decode('{"response_code" : "OK", "response_message" : "Option Deleted successfully"}', true)]];
    }
}


