<?php  namespace redhotmayo\billing;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class BillingServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $service = Config::get('billing.service');

        $this->app->bind('redhotmayo\billing\BillingInterface', $service);
        $this->app->bind('BillingInterface', $service);

        $this->app->bind('redhotmayo\billing\BillingService', $service);
        $this->app->bind('BillingService', $service);
    }
}
