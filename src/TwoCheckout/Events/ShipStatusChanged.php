<?php
namespace TwoCheckout\Events;

use TwoCheckout\Contracts\Abstracts\BaseEvent;

/**
 * @link https://www.2checkout.com/documentation/notifications/refund-issued
 * Ship Status Changed is an invoice level message; it will be sent once for each shipping status change on an invoice and will contain information about all items ordered.
 */
class ShipStatusChanged extends BaseEvent
{
    const TYPE = 'SHIP_STATUS_CHANGED';
}