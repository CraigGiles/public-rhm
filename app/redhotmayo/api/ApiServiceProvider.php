<?php namespace redhotmayo\api;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use redhotmayo\api\auth\ApiAuthorizer;
use redhotmayo\auth\AuthorizationService;
use redhotmayo\dataaccess\repository\sql\UserRepositorySQL;

class ApiServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('redhotmayo\auth\AuthorizationService', AuthorizationService::SERVICE);
        $this->app->bind('AuthorizationService', AuthorizationService::SERVICE);

        // Register 'api_authorizer' instance container to our ApiAuthorizer object
        $this->app['api_authorizer'] = $this->app->share(function($app)
        {
            $service = App::make('AuthorizationService');
            return new ApiAuthorizer($service);
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('ApiAuthorizer', 'redhotmayo\api\auth\ApiAuthorizer');
        });
    }
}
