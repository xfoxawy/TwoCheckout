<?php
namespace TwoCheckout\Events;

use TwoCheckout\Contracts\Abstracts\BaseEvent;

/**
 * @link https://www.2checkout.com/documentation/notifications/recurring-restarted
 * Recurring Restarted is an item level message; it will be sent once for each recurring item restarted and will only contain information about that item.
 */
class RecurringRestarted extends BaseEvent
{
    const TYPE = 'RECURRING_RESTARTED';
}