<?php
namespace TwoCheckout\Events;

use TwoCheckout\Contracts\Abstracts\BaseEvent;

/**
 * @link https://www.2checkout.com/documentation/notifications/refund-issued
 * Refund Issued is an item level message; it will be sent once for each refund item issued (product or partial) and will only contain information about that item.
 */
class RefundIssued extends BaseEvent
{
    const TYPE = 'REFUND_ISSUED';
}