<?php
namespace TwoCheckout\Events;

use TwoCheckout\Contracts\Abstracts\BaseEvent;

/**
 * @link https://www.2checkout.com/documentation/notifications/recurring-installment-success
 * Recurring Installment Success is an item level message; it will be sent once for each recurring item billed successfully and will only contain information about that item.
 */
class RecurringInstallmentSuccess extends BaseEvent
{
    const TYPE = 'RECURRING_INSTALLMENT_SUCCESS';
}