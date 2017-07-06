<?php
namespace TwoCheckout\Events;

use TwoCheckout\Contracts\Abstracts\BaseEvent;

/**
 * @link https://www.2checkout.com/documentation/notifications/order-created
 * Order Created is an invoice level message; it will be sent once for each new sale and will contain information about all items ordered.
 */
class OrderCreated extends BaseEvent
{
    const TYPE = 'ORDER_CREATED';
}