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
