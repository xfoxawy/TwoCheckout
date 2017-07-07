<?php
namespace TwoCheckout;

use Illuminate\Support\ServiceProvider;
use TwoCheckout\TwoCheckout;
use TwoCheckout\Adapters\TwoCheckoutHttpAdapter;
use TwoCheckout\Adapters\TwoCheckoutConfigAdapter;
use GuzzleHttp\Client as Guzzle;

/**
* 2CheckoutServiceProvider
*/
class TwoCheckoutServiceProvider extends ServiceProvider
{
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Bootable/config.php' => config_path('2checkout.php'),
            __DIR__.'/Bootable/views' => resource_path('views/vendor/2co')
        ]);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTwoCheckout();  
    }

    private function registerTwoCheckout(){
        $config = (isset($this->app['config']['2checkout'])) ? $this->app['config']['2checkout'] : ['ssl_cert_path'=>'default'];
        $config = new TwoCheckoutConfigAdapter($config);
        $instance = new TwoCheckout(new TwoCheckoutHttpAdapter($config, new Guzzle()));
        
        $this->app->singleton('twocheckout', function() use($instance){
            return $instance;
        });
            
        $this->app->instance('TwoCheckout\TwoCheckout', $instance);
    }

    public function provides(){
        return ['twocheckout'];
    }

}