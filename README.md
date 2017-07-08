# TwoCheckout Laravel's Service Provider
Providing Simple Integration and expressive interface for 2Checkout Payment Gateway
## Requirements
* php >= 5.6
* Laravel >= 5

## Installation

* Use following command to install:

```bash
composer require "xfoxawy/2checkout:dev-master"
```

* Add the service provider to your `$providers` array in `config/app.php` file like: 

```php
TwoCheckout\TwoCheckoutServiceProvider // Laravel 5
```
```php
TwoCheckout\TwoCheckoutServiceProvider::class // Laravel 5.1 or greater
```

* Add the alias to your `$aliases` array in `config/app.php` file like: 

```php
'TwoCheckout' => TwoCheckout\Facades\TwoCheckout // Laravel 5
```
```php
'TwoCheckout' => TwoCheckout\Facades\TwoCheckout::class // Laravel 5.1 or greater
```

* Run the following command to publish configuration:

```bash
php artisan vendor:publish
```

## Usage
### Configuration 
* after creating 2checkout account and creating API credentials 
fill in API creds in `config/2checkout.php`

### Class DI Example
```php
class ExampleController
{
    public function __construct(\TwoCheckout\TwoCheckout $tco)
    {
        $this->tco = $tco;
    }

    public function product()
    {
        $this->tco->Product()->get($product_id);
    }
}
```
### Using Facade
```php
class ExampleController
{

    public function product()
    {
        TwoCheckout::Sale()->list();
    }
}
```

***

# TwoCheckout API 
TwoCheckout Provides expressive OOP access interface, each EndPoint / Event is Represented by Class with regular CRUD interface (get/create/update/delete) representing 2Checkout Restful API.  

TwoCheckout Admin API is represented By `Resources` classes, and TwoCheckout Webhooks Events are represented by `Events` classes, for more information please review 2Checkout [Documentation](https://www.2checkout.com/documentation).    
the following Walkthrough will guide you to each class normal usage.  

## Resources
### Account
### Payment
### Prouduct
### Coupon
### Option
### Sale
 
## Events
to receive 2Checkout Webhook Event, in your route method.  
`listenTo` method verifies and returns Event as `Array`.  
for more info about the return data for each event please review the [Documentation](https://www.2checkout.com/documentation/notifications/).  
```php
public function receive($event_data)
{
    $event = TwoCheckout::listenTo($event_data);
}
```
## Supported Events
* FraudStatusChanged
* InvoiceStatusChanged
* OrderCreated
* RecurringComplete
* RecurringInstallmentFailed
* RecurringInstallmentSuccess
* RecurringRestarted
* RecurringStopped
* RefundIssued
* ShipStatusChanged
