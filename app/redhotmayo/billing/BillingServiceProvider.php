<?php  namespace redhotmayo\billing;

use Illuminate\Support\ServiceProvider;
use redhotmayo\billing\stripe\StripeBilling;

class BillingServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $stripe = 'redhotmayo\billing\stripe\StripeBilling';
        $this->app->bind('redhotmayo\billing\BillingInterface', $stripe);
        $this->app->bind('BillingInterface', $stripe);
    }
}
