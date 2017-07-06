<?php
namespace TwoCheckout\Facades;

use  Illuminate\Support\Facades\Facade;

/**
 * @see \TwoCheckout\TwoCheckoutGateway
 */
class TwoCheckout extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'twocheckout';
    }
}