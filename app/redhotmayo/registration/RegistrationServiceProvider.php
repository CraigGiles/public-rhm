<?php namespace redhotmayo\registration;

use Illuminate\Support\ServiceProvider;

class RegistrationServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register 'registration' instance container to our Registration object
        $this->app['registration'] = $this->app->share(function($app)
        {
            return new Registration();
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Registration', 'redhotmayo\registration\Registration');
        });
    }
}