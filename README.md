TwoCheckout Laravel's Service Provider
===============================



----------
Installtion
-------------
composer install xfoxawy/2checkout

in config/app
register TwoCheckout\TwoCheckoutServiceProvider::class to Providers array
register TwoCheckout => TwoCheckout\Facades\TwoCheckout::class to Facades Array

php artisan vendor:publish --tag=config to publish config file
php artisan vendor:publish --tag=views to publish view example file

You can use the "views/vendor/2co/example.blade.php" as test template

Documentation
-------------

This Project is still under Development, wait for updates on the documentation and how to use manual
