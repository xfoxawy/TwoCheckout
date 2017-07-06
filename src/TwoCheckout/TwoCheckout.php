<?php
namespace TwoCheckout;

use Twocheckout\Adapters\TwoCheckoutHttpAdapter as HttpAdapter;
use TwoCheckout\Exceptions\TwoCheckoutException;

/**
 * TwoCheckout API Access Interface
 */
class TwoCheckout
{
    /**
     * Twocheckout\Adapters\TwoCheckoutConfigAdapter
     * @var Object
     */
    private $config;

    /**
     * Twocheckout\Adapters\TwoCheckoutHttpAdapter
     * @var Object
     */
    private $adapter;


    public function __construct(HttpAdapter $adapter)
    {
        $this->adapter = $adapter;
        $this->config = $adapter->config;
    }

    /**
     * Access Config Array
     * @return Mixed 
     */
    public function config(){
        return $this->config;
    }

    /**
     * Access API Resources
     * @link https://www.2checkout.com/documentation/api/
     * @param  String $name   Resource name
     * @return Object         TwoCheckout\Resource\BaseResource
     */
    public function __call($name, $params){
        if(in_array(ucfirst($name),['Account','Sale','Product','Coupon','Option'])){
            $class = "TwoCheckout\\Resources\\".ucfirst($name);
            return new $class($this->adapter);
        }
        throw new TwoCheckoutException("Invalid Resource name/identifier {$name}", 1);
    }

    /**
     * Listens to Webhooks and iniate Event Class
     * @link https://www.2checkout.com/documentation/notifications/
     * @param  Array  $event Posted Event Data through Webhooks
     * @return Object        TwoCheckout\Events\BaseEvent
     */
    public function listenTo(array $event){
        $event_types = [
            'ORDER_CREATED', 
            'FRAUD_STATUS_CHANGED', 
            'SHIP_STATUS_CHANGED', 
            'INVOICE_STATUS_CHANGED', 
            'REFUND_ISSUED', 
            'RECURRING_INSTALLMENT_SUCCESS', 
            'RECURRING_INSTALLMENT_FAILED', 
            'RECURRING_STOPPED', 
            'RECURRING_COMPLETE', 
            'RECURRING_RESTARTED'
        ];

        $type = (isset($event['message_type'])) ? $event['message_type'] : null;
        
        if(isset($type) && in_array($type, $event_types))
        {
            $className = str_replace('_','', ucwords(strtolower($type),'_'));
            $className = "TwoCheckout\\Events\\".$className;
            $class = new $className($this->config->secret_word);
            return $class->parse($event);
        }
        
        throw new TwoCheckoutException("Invalid Event type/identifier {$type}");
    }
}