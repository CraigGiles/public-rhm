<?php namespace redhotmayo\api;

use Illuminate\Support\ServiceProvider;
use redhotmayo\api\auth\ApiAuthorizer;

class ApiServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register 'api_authorizer' instance container to our ApiAuthorizer object
        $this->app['api_authorizer'] = $this->app->share(function($app)
        {
            return new ApiAuthorizer();
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('ApiAuthorizer', 'redhotmayo\api\auth\ApiAuthorizer');
        });
    }
}
