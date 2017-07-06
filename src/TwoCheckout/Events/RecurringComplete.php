<?php
namespace TwoCheckout\Events;

use TwoCheckout\Contracts\Abstracts\BaseEvent;

/**
 * @link https://www.2checkout.com/documentation/notifications/recurring-complete
 * Recurring Complete is an item level message; it will be sent once for each recurring item completed and will only contain information about that item.
 */
class RecurringComplete extends BaseEvent
{
    const TYPE = 'RECURRING_COMPLETE';
}