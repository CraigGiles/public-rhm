<?php

use Illuminate\Support\ServiceProvider;

class DataAccessServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        //if we're using SQL:
        $this->app->bind('AccountRepository', 'AccountRepositorySQL');
        $this->app->bind('AddressRepository', 'AddressRepositorySQL');
        $this->app->bind('NoteRepository', 'NoteRepositorySQL');
        $this->app->bind('SubscriptionRepository', 'SubscriptionRepositorySQL');

    }
}