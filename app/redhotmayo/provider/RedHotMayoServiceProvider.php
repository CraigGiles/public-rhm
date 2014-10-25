<?php  namespace redhotmayo\provider;

use Illuminate\Support\ServiceProvider;

abstract class RedHotMayoServiceProvider extends ServiceProvider {
    /**
     * Sets up any dependencies needed to construct the service and returns
     * the newly constructed service to the caller
     *
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public abstract function getService();

    public abstract function getServiceName();
    public abstract function getAliasName();
    public abstract function getAliasValue();

    /**
     * Register the service provider.
     *
     * @return mixed
     * @author Craig Giles < craig@gilesc.com >
     */
    public function register() {
        $serviceName = $this->getServiceName();

        $this->app[$serviceName] = $this->app->share(function($app) {
            $this->getService();
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function() {
            $name = $this->getAliasName();
            $value = $this->getAliasValue();

            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias($name, $value);
        });
    }
}
