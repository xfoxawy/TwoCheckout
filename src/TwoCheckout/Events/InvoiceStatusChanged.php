<?php
namespace TwoCheckout\Events;

use TwoCheckout\Contracts\Abstracts\BaseEvent;

/**
 * @link https://www.2checkout.com/documentation/notifications/invoice-status-changed
 * Invoice Status Changed is an invoice level message; it will be sent once for each status change on an invoice and will contain information about all items ordered.
 */
class InvoiceStatusChanged extends BaseEvent
{
    const TYPE = 'INVOICE_STATUS_CHANGED';
}