<?php
namespace TwoCheckout\Events;

use TwoCheckout\Contracts\Abstracts\BaseEvent;

/**
 * @link https://www.2checkout.com/documentation/notifications/recurring-stopped
 * Recurring Stopped is an item level message; it will be sent once for each recurring item stopped and will only contain information about that item.
 */
class RecurringStopped extends BaseEvent
{
    const TYPE = 'RECURRING_STOPPED';
}