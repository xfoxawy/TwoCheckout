<?php
namespace TwoCheckout\Tests\Resources;

use PHPUnit\Framework\TestCase;
use TwoCheckout\Adapters\TwoCheckoutConfigAdapter;
use TwoCheckout\Adapters\TwoCheckoutHttpAdapter;
use TwoCheckout\Resources\Product;

class ProductTest extends TestCase
{
    public function setUp()
    {
        $this->adapter = \Mockery::mock(TwoCheckoutHttpAdapter::class)->makePartial();
        $this->adapter->config = new \StdClass();
        $this->adapter->config->base_uri = 'https://www.2checkout.com/';
    }

    /**
     * @dataProvider listProductsProvider
     */
    public function testListProducts($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $product = new Product($this->adapter);
        $list = $product->list();
        $this->assertNotNull($list);
        $this->assertCount(2, $list);
        $this->assertEquals('10', $list[0]['assigned_product_id']);
    }

    public function listProductsProvider()
    {
        return [[json_decode('{"page_info" : {"cur_page" : "1", "first_entry" : 1, "first_page" : 1, "first_page_url" : "https://www.2checkout.com/api/products/list_products?cur_page=1", "last_entry" : "15", "last_page" : 1, "last_page_url" : "https://www.2checkout.com/api/products/list_products?cur_page=1", "next_page" : null, "pagesize" : "20", "previous_page" : null, "total_entries" : "15"}, "products" : [{"approved_url" : null, "assigned_product_id" : "10", "categories" : [{"category_id" : "17", "description" : null, "name" : "Photography", "parent_id" : "1", "parent_name" : "Art & Antiques"} ], "commission" : null, "commission_type" : null, "description" : "Product with options.", "duration" : null, "handling" : "0.00", "images" : [], "long_description" : null, "name" : "OPTION TEST", "options" : [{"option_id" : "4688550010", "option_name" : "Volume", "option_values" : [{"option_value_id" : "4688550013", "option_value_name" : "Low", "option_value_surcharge" : "1.00"}, {"option_value_id" : "4688550016", "option_value_name" : "Medium", "option_value_surcharge" : "2.00"}, {"option_value_id" : "4688550019", "option_value_name" : "High", "option_value_surcharge" : "3.00"} ] } ], "pending_url" : null, "price" : "100.00", "product_id" : "4274737762", "recurrence" : null, "startup_fee" : null, "tangible" : "0", "vendor_id" : "1311348", "vendor_product_id" : "OP-T", "weight" : null }, {"approved_url" : null, "assigned_product_id" : "5", "categories" : [{"category_id" : "80", "description" : null, "name" : "Education", "parent_id" : null, "parent_name" : null } ], "commission" : null, "commission_type" : null, "description" : null, "duration" : "Forever", "handling" : "0.00", "images" : [], "long_description" : null, "name" : "Anonymous Online Magazine", "options" : [], "pending_url" : null, "price" : "24.00", "product_id" : "4124411144", "recurrence" : "1 Month", "startup_fee" : null, "tangible" : "0", "vendor_id" : "1311348", "vendor_product_id" : "OMG-0001", "weight" : null } ], "response_code" : "OK", "response_message" : "Product list successfully retrieved."}', true)]];
    }

    /**
     * @dataProvider createProductProvider
     */
    public function testCreateProduct($data)
    {  
        $this->adapter->shouldReceive('request')->andReturn($data);
        $product = new Product($this->adapter);
        $res = $product->new('testProduct',22.23);
        $this->assertNotNull($res);
        $this->assertEquals($res['assigned_product_id'], '2560');
        $this->assertEquals($res['product_id'], '4688359093');
    }

    public function createProductProvider()
    {
        return [[json_decode('{"assigned_product_id" : "2560", "product_id" : "4688359093", "response_code" : "OK", "response_message" : "Product successfully created"}', true)]];
    }

    /**
     * @dataProvider getProductProvider
     */
    public function testGetProduct($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $product = new Product($this->adapter);
        $res = $product->get('4635212971');
        $this->assertNotNull($res);
        $this->assertEquals($res['assigned_product_id'], '2559');
    }

    public function getProductProvider()
    {
        return  [[json_decode('{"product" : {"approved_url" : null, "assigned_product_id" : "2559", "categories" : [], "commission" : 0, "commission_amount" : null, "commission_type" : null, "description" : "An API created product", "duration" : null, "handling" : null, "images" : [], "long_description" : null, "name" : "API product", "options" : [{"option_id" : "4023756741", "option_name" : "extra benefit", "option_values" : [{"option_value_id" : "4023756744", "option_value_name" : "full life coverage", "option_value_surcharge" : "1.00"}, {"option_value_id" : "4023756747", "option_value_name" : "half life coverage", "option_value_surcharge" : "0.50"}, {"option_value_id" : "4023756750", "option_value_name" : "extra lives coverage", "option_value_surcharge" : "2.00"} ] }, {"option_id" : "4688355343", "option_name" : "decibels", "option_values" : [{"option_value_id" : "4688355346", "option_value_name" : "150dB", "option_value_surcharge" : "11.00"}, {"option_value_id" : "4688441332", "option_value_name" : "200dB", "option_value_surcharge" : "22.00"}, {"option_value_id" : "4688441335", "option_value_name" : "250dB", "option_value_surcharge" : "33.00"} ] } ], "pending_url" : null, "price" : "10.00", "product_id" : "4635212971", "recurrence" : null, "recurrence_p" : null, "recurring" : "0", "startup_fee" : null, "tangible" : "0", "vendor_id" : "606917", "vendor_product_id" : "", "weight" : null }, "response_code" : "OK", "response_message" : "Product detail information retrieved successfully"}', true)]];
    }

    /**
     * @dataProvider updateProductProvider
     */
    public function testUpdateProduct($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $product = new Product($this->adapter);
        $res = $product->update('4691409938', ['description'=>'new test description']);
        $this->assertNotNull($res);
        $this->assertTrue($res);
    }

    public function updateProductProvider()
    {
        return [[json_decode('{"assigned_product_id" : "2561", "product_id" : "4691409938", "response_code" : "OK", "response_message" : "Product successfully updated"}', true)]];
    }

    /**
     * @dataProvider deleteProductProvider
     */
    public function testDeleteProduct($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $product = new Product($this->adapter);
        $res = $product->delete('4688355343');
        $this->assertNotNull($res);
        $this->assertTrue($res);
    }

    public function deleteProductProvider()
    {
        return [[json_decode('{"response_code" : "OK", "response_message" : "Product successfully deleted."}', true)]];
    }
}