<?php  namespace redhotmayo\billing;

use Illuminate\Support\ServiceProvider;

class BillingServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $stripe = 'redhotmayo\billing\stripe\StripeBillingService';
        $this->app->bind('redhotmayo\billing\BillingInterface', $stripe);
        $this->app->bind('BillingInterface', $stripe);

        $this->app->bind('redhotmayo\billing\BillingService', $stripe);
        $this->app->bind('BillingService', $stripe);
    }
}
