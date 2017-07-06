<?php
namespace TwoCheckout\Resources;

use TwoCheckout\Contracts\Abstracts\ImmutableResource as BaseResource;
use TwoCheckout\Exceptions\TwoCheckoutException;

/**
 * Payment Access Interface
 */
class Payment extends BaseResource
{
    /**
     * List of API callables 
     * @var Array
     */
    protected $callables = [
        'detail_pending_payment' => [
            'uri' => '/api/acct/detail_pending_payment',
            'method' => 'GET'
        ],
        'list_payments' => [
            'uri' => '/api/acct/list_payments',
            'method' => 'GET'
        ]
    ];

    /**
     * The detail_pending_payment call is used to get a detailed estimate of the current pending payment.
     * @link https://www.2checkout.com/documentation/api/account/detail-pending-payment
     * @return Array Pending Payment Details
     */
    public function getPendingPayment(){
        $retrieved = $this->retrieve(['detail_pending_payment']);
        return $retrieved['payment'];
    }

    /**
     * The list_payments call is used to get a list of past payments.
     * @link https://www.2checkout.com/documentation/api/account/list-payments
     * @return Array All Past Payments Details
     */
    public function list(){
        $retrieved = $this->retrieve(['list_payments']);
        return $retrieved['payments'];
    }    
}