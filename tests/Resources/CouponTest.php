<?php
namespace TwoCheckout\Tests\Resources;

use PHPUnit\Framework\TestCase;
use TwoCheckout\Adapters\TwoCheckoutConfigAdapter;
use TwoCheckout\Adapters\TwoCheckoutHttpAdapter;
use TwoCheckout\Resources\Coupon;

class CouponTest extends TestCase
{
    public function setUp()
    {
        $this->adapter = \Mockery::mock(TwoCheckoutHttpAdapter::class)->makePartial();
    }

    /**
     * @dataProvider listCouponsProvider
     */
    public function testListCoupons($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $coupon = new Coupon($this->adapter);
        $list = $coupon->list();
        $this->assertNotNull($list);
        $this->assertCount(5 , $list);
        $this->assertEquals($list[0]['coupon_code'], "TESTPERCENT");
    }

    public function listCouponsProvider()
    {
        return [[
            json_decode('{"coupon" : [{"coupon_code" : "TESTPERCENT", "date_expire" : "2013-06-30", "minimum_purchase" : "1.00", "percentage_off" : "0.10", "type" : "product", "value_off" : null }, {"coupon_code" : "APITEST002", "date_expire" : "2012-12-31", "minimum_purchase" : "5.00", "percentage_off" : "0.05", "type" : "sale", "value_off" : null }, {"coupon_code" : "APITEST003", "date_expire" : "2012-12-31", "minimum_purchase" : "5.00", "percentage_off" : "0.01", "type" : "sale", "value_off" : null }, {"coupon_code" : "APITEST004", "date_expire" : "2012-12-31", "minimum_purchase" : "5.00", "percentage_off" : "0.02", "type" : "sale", "value_off" : null }, {"coupon_code" : "PERCENTALL", "date_expire" : "2012-05-04", "minimum_purchase" : "10.00", "percentage_off" : "0.10", "type" : "product", "value_off" : null } ], "response_code" : "OK", "response_message" : "Coupon information retrieved successfully."}', true)
        ]];
    }

    /**
     * @dataProvider getCouponProvider
     */
    public function testGetCoupon($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $coupon = new Coupon($this->adapter);
        $list = $coupon->get('APITEST002');
        $this->assertNotNull($list);
        $this->assertEquals($list['coupon_code'], "APITEST002");
        $this->assertNotNull($list['product']);
    }

    public function getCouponProvider()
    {
        return [[json_decode('{"coupon" : {"coupon_code" : "APITEST002", "date_expire" : "2012-12-31", "minimum_purchase" : "5.00", "percentage_off" : "5", "product" : [{"product_id" : "0", "product_url" : "https://www.2checkout.com/api/products/detail_product?product_id=0"} ], "type" : "sale", "value_off" : null }, "response_code" : "OK", "response_message" : "Coupon detail retrieved successfully."}',true)]];
    }

    /**
     * @dataProvider createCouponProvider
     */
    public function testCreateCoupon($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $coupon = new Coupon($this->adapter);
        $res = $coupon->new('2017-01-01','sale');
        $this->assertNotNull($res);
        $this->assertEquals($res, 'APITEST004');

    }

    public function createCouponProvider()
    {
         return[[
            json_decode('{"coupon_code" : "APITEST004", "response_code" : "OK", "response_message" : "Coupon successfully created"}', true)
         ]];
    }

    public function testUpdateCoupon()
    {
        $this->adapter->shouldReceive('request')->andReturn(null);
        $coupon = new Coupon($this->adapter);
        $res = $coupon->update('APITEST004', ['type' => 'shipping']);
        $this->assertTrue($res);

    }

    public function testDeleteCoupon()
    {
        $this->adapter->shouldReceive('request')->andReturn(null);
        $coupon = new Coupon($this->adapter);
        $res = $coupon->delete('APITEST004');
        $this->assertTrue($res);
    }

}
