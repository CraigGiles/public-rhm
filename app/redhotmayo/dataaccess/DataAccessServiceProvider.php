<?php namespace redhotmayo\dataaccess;

use Illuminate\Support\ServiceProvider;
use redhotmayo\dataaccess\repository\sql\ZipcodeRepositorySQL;

class DataAccessServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        //todo: if we're using SQL... :
        $this->app->bind('redhotmayo\dataaccess\repository\AccountRepository', 'redhotmayo\dataaccess\repository\sql\AccountRepositorySQL');
        $this->app->bind('redhotmayo\dataaccess\repository\UserRepository', 'redhotmayo\dataaccess\repository\sql\UserRepositorySQL');
        $this->app->bind('redhotmayo\dataaccess\repository\AddressRepository', 'AddressRepositorySQL');
        $this->app->bind('redhotmayo\dataaccess\repository\NoteRepository', 'NoteRepositorySQL');
        $this->app->bind('redhotmayo\dataaccess\repository\SubscriptionRepository', 'SubscriptionRepositorySQL');
        $this->app->bind('redhotmayo\dataaccess\repository\ZipcodeRepository', ZipcodeRepositorySQL::SERVICE);

    }
}