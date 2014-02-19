<?php namespace redhotmayo\registration;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use redhotmayo\dataaccess\repository\RepositoryFactory;
use redhotmayo\mailers\UserMailer;

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
            $userRepo = RepositoryFactory::GetUserRepository();
            //todo: inject this
            $userMailer = new UserMailer();
            return new Registration($userRepo, $userMailer);
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Registration', 'redhotmayo\registration\Registration');
        });
    }
}