<?php
namespace TwoCheckout\Contracts\Abstracts;

use TwoCheckout\Exceptions\TwoCheckoutException;

/**
 * @link https://www.2checkout.com/documentation/notifications/
 * Instant Notification Service (INS) to automate order management processes by accepting order information via web 
 * posts. The INS is a service which will post sets of parameters to any URL you specify. Each post represents a message 
 * containing all the information you need about a specific event
 */
abstract class BaseEvent
{
    /**
     * 2Checkout Seller ID
     * @var String
     */
    private $seller_id;

    /**
     * 2Checkout Secert Word
     * @var String
     */
    private $secret_word;

    /**
     * Parsed Event Data
     * @var Array
     */
    protected $event;
    
    public function __construct($secret_word)
    {
        $this->secret_word = $secret_word;
    }

    /**
     * Parse Event's Posted Data
     * @param  Array  $event 
     * @return Object/Self
     * @throws TwoCheckout\Exceptions\TwoCheckoutException
     */
    public function parse(array $event)
    {
        $this->event = $this->validate($event);
        return $this;
    }

    /**
     * Validates and Sanitize Event's MD5 Hash
     * @param  Array  $event Posted Event Data
     * @return Boolean     
     */
    private function validate(array $event)
    {
        if(isset($event['invoice_id']) && isset($event['sale_id']) && isset($event['vendor_id']) && isset($event['md5_hash']))
        {
            $hash = strtoupper(md5($event['sale_id'] . $event['vendor_id'] . $event['invoice_id'] . $this->secret_word));
            if($hash == $event['md5_hash']){
                unset($event['md5_hash']);
                return $event;
            }
        }
        throw new TwoCheckoutException("Error Parsing Invalid Event");
    }

    /**
     * Retrieve Event's Value By Key
     * @param  String $key 
     * @return Mixed      
     */
    public function __get($key)
    {
        if(isset($this->event[$key]))
        {
            return $this->event[$key];
        }
        return null;
    }

    /**
     * Get Event Type
     * @return String 2Checkout Event Type
     */
    public function type()
    {  
        return static::TYPE;
    }
}