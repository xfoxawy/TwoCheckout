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
        //
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
        $config = new TwoCheckoutConfigAdapter($this->app['config']['2checkout']);
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