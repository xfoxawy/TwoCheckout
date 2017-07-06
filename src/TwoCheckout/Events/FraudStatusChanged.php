<?php
namespace TwoCheckout\Events;

use TwoCheckout\Contracts\Abstracts\BaseEvent;

/**
 * @link https://www.2checkout.com/documentation/notifications/fraud-status-changed
 * Fraud Status Changed is an invoice level message; it will be sent once for each fraud status change on a sale and will contain information about all items ordered.
 */
class FraudStatusChanged extends BaseEvent
{
    const TYPE = 'FRAUD_STATUS_CHANGED';
}