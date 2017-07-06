<?php
namespace TwoCheckout\Events;

use TwoCheckout\Contracts\Abstracts\BaseEvent;

/**
 * @link https://www.2checkout.com/documentation/notifications/recurring-installment-failed
 * Recurring Installment Failed is an item level message; it will be sent once for each recurring item which fails to bill and will only contain information about that item.
 */
class RecurringInstallmentFailed extends BaseEvent
{
    const TYPE = 'RECURRING_INSTALLMENT_FAILED';
}