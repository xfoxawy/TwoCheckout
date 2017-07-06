<?php
namespace TwoCheckout\Tests;
use PHPUnit\Framework\TestCase;
use TwoCheckout\Events\RefundIssued;

class RefundIssuedTest extends TestCase
{
    public function setUp()
    {
        $secret_word = 'tHeBroWnFoXjUmpSoVerTheLazyDoG';
        $this->event = new RefundIssued($secret_word);
    }

  
    /**
     * @dataProvider validEventProvider
     */
    public function testListenToValidEvent($event)
    {
        $event = $this->event->parse($event);
        $this->assertEquals('532001',$event->vendor_id);
        $this->assertEquals('REFUND_ISSUED',$event->type());
        $this->assertNull($event->md5_hash);
        $this->assertInstanceOf(\TwoCheckout\Contracts\Abstracts\BaseEvent::class, $event);
    }

    /**
     * @dataProvider invalidEventProvider
     * @expectedException TwoCheckout\Exceptions\TwoCheckoutException
     */
    public function testListenToInvalidEvent($event)
    {
        $this->event->parse($event);
    }

    public function validEventProvider()
    {
        return [[(json_decode('{"bill_city" : "Columbus ", "bill_country" : "USA ", "bill_postal_code" : "43123 ", "bill_state" : "OH ", "bill_street_address" : "123 Test St ", "bill_street_address2" : " ", "cust_currency" : "USD ", "customer_email" : "christensoncraig@gmail.com ", "customer_first_name" : "Craig ", "customer_ip" : "66.194.132.135 ", "customer_ip_country" : "United States ", "customer_last_name" : "Christenson ", "customer_name" : "Craig P Christenson ", "customer_phone" : "5555555555 ", "invoice_id" : "4707205064", "item_count" : "1 ", "item_cust_amount_1" : "0.01 ", "item_duration_1" : "2 Month ", "item_id_1" : "ebook2 ", "item_list_amount_1" : "0.01 ", "item_name_1" : "test recurring product ", "item_rec_date_next_1" : " ", "item_rec_install_billed_1" : "1 ", "item_rec_list_amount_1" : "0.01 ", "item_rec_status_1" : " ", "item_recurrence_1" : "1 Week ", "item_type_1" : "refund ", "item_usd_amount_1" : "0.01 ", "key_count" : "50 ", "list_currency" : "USD ", "md5_hash" : "04215808D538E94DF856949489D0D8CA", "message_description" : "Refund issued", "message_id" : "3197", "message_type" : "REFUND_ISSUED", "payment_type" : "credit card ", "recurring" : "1 ", "sale_date_placed" : "2012-05-14 06:29:53 ", "sale_id" : "4707205055", "ship_city" : " ", "ship_country" : " ", "ship_name" : " ", "ship_postal_code" : " ", "ship_state" : " ", "ship_status" : " ", "ship_street_address" : " ", "ship_street_address2" : " ", "ship_tracking_number" : " ", "timestamp" : "2012-05-14 06:34:26 ", "vendor_id" : "532001", "vendor_order_id" : "test123 "}', true))]];
    }

    public function invalidEventProvider()
    {
        return [[(json_decode('{"bill_city" : "Columbus ", "bill_country" : "USA ", "bill_postal_code" : "43123 ", "bill_state" : "OH ", "bill_street_address" : "123 Test St ", "bill_street_address2" : " ", "cust_currency" : "USD ", "customer_email" : "christensoncraig@gmail.com ", "customer_first_name" : "Craig ", "customer_ip" : "66.194.132.135 ", "customer_ip_country" : "United States ", "customer_last_name" : "Christenson ", "customer_name" : "Craig P Christenson ", "customer_phone" : "5555555555 ", "invoice_id" : "4707205064", "item_count" : "1 ", "item_cust_amount_1" : "0.01 ", "item_duration_1" : "2 Month ", "item_id_1" : "ebook2 ", "item_list_amount_1" : "0.01 ", "item_name_1" : "test recurring product ", "item_rec_date_next_1" : " ", "item_rec_install_billed_1" : "1 ", "item_rec_list_amount_1" : "0.01 ", "item_rec_status_1" : " ", "item_recurrence_1" : "1 Week ", "item_type_1" : "refund ", "item_usd_amount_1" : "0.01 ", "key_count" : "50 ", "list_currency" : "USD ", "md5_hash" : "04215808D638E0PDF856949489D0D8CA", "message_description" : "Refund issued", "message_id" : "3197", "message_type" : "REFUND_ISSUED", "payment_type" : "credit card ", "recurring" : "1 ", "sale_date_placed" : "2012-05-14 06:29:53 ", "sale_id" : "4707205055", "ship_city" : " ", "ship_country" : " ", "ship_name" : " ", "ship_postal_code" : " ", "ship_state" : " ", "ship_status" : " ", "ship_street_address" : " ", "ship_street_address2" : " ", "ship_tracking_number" : " ", "timestamp" : "2012-05-14 06:34:26 ", "vendor_id" : "532001", "vendor_order_id" : "test123 "}', true))]];
    }
}