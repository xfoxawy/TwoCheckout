# TwoCheckout Laravel's Service Provider
Providing Simple Integration and expressive interface for 2Checkout Payment Gateway
## Requirements
* php >= 5.6
* Laravel >= 5

# Installtion
* using composer `composer require "xfoxawy/2checkout:dev-master"`.
* add `TwoCheckout\TwoCheckoutServiceProvider::class` Service Providers array in `config/app.php`.
* add `'TwoCheckout' => TwoCheckout\Facades\TwoCheckout::class` to Alias array in `config/app.php`. 
* publish config file and exampel view file, `php artisan vendor:publish`.
