<?php
namespace TwoCheckout\Tests\Resources;

use PHPUnit\Framework\TestCase;
use TwoCheckout\Adapters\TwoCheckoutConfigAdapter;
use TwoCheckout\Adapters\TwoCheckoutHttpAdapter;
use TwoCheckout\Resources\Payment;

class PaymentTest extends TestCase
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
        $payment = new Payment($this->adapter);
        $payment->create([]);
    }

    /**
     * @expectedException TwoCheckout\Exceptions\TwoCheckoutException
     */
    public function testUpdate()
    {
        $payment = new Payment($this->adapter);
        $payment->update([]);
    }

     /**
     * @expectedException TwoCheckout\Exceptions\TwoCheckoutException
     */
    public function testDelete()
    {
        $payment = new Payment($this->adapter);
        $payment->delete([]);
    }

    /**
     * @dataProvider pendingPaymentProvider
     */
    public function testGetPendingPayment($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $payment = new Payment($this->adapter);
        $info = $payment->getPendingPayment();
        $this->assertNotNull($info);
        $this->assertEquals($info['amount'], "999.99");
        $this->assertEquals($info['total_commissions'], "0.21");
    }

    public function pendingPaymentProvider()
    {
        return [[
            json_decode('{"payment" : {"amount" : "999.99", "payment_fee" : "0.00", "payment_id" : "2436561667", "payment_method" : "ach", "release_level" : "15", "reserve_held" : "14.19", "total_adjustments" : "61.07", "total_balance_forward" : "0.00", "total_chargeback_fees" : "0.00", "total_commissions" : "0.21", "total_fees" : "86.92", "total_outgoing_commissions" : "4.35", "total_refunds" : "275.36", "total_reserve_released" : "0.00", "total_sales" : "283.86"}, "response_code" : "OK", "response_message":"payment info retrieved"}', true)
        ]];
    }

     /**
     * @dataProvider listPaymentsProvider
     */
    public function testListPayments($data)
    {
        $this->adapter->shouldReceive('request')->andReturn($data);
        $payment = new Payment($this->adapter);
        $info = $payment->list();
        $this->assertCount(2, $info);
        $this->assertEquals($info[0]['amount'], "50240.51");
        $this->assertNotNull($info);
    }

    public function listPaymentsProvider()
    {
        return [[
            json_decode('{"payments" : [{"amount" : "50240.51", "date_paid" : null, "date_reserve_released" : null, "date_voided" : null, "payment_id" : "4681565342", "payment_identifier" : "wire_999042012", "payment_type" : "wire", "reserve_held" : "1646.07", "total_adjustments" : "0.00", "total_balance_forward" : "0.00", "total_chargeback_fees" : "0.00", "total_commissions" : "0.00", "total_fees" : "1941.34", "total_outgoing_commissions" : "0.00", "total_refunds" : "2418.46", "total_reserve_released" : "1397.38", "total_sales" : "54869.00", "void" : null }, {"amount" : "45616.52", "date_paid" : null, "date_reserve_released" : null, "date_voided" : null, "payment_id" : "4676285423", "payment_identifier" : "wire_992042012", "payment_type" : "wire", "reserve_held" : "1499.83", "total_adjustments" : "0.00", "total_balance_forward" : "0.00", "total_chargeback_fees" : "0.00", "total_commissions" : "0.00", "total_fees" : "1768.57", "total_outgoing_commissions" : "0.00", "total_refunds" : "2553.00", "total_reserve_released" : "1463.62", "total_sales" : "49994.30", "void" : null } ], "response_code" : "OK", "response_message" : "payment info retrieved"}', true)
        ]];
    }
}