<?php namespace redhotmayo\dataaccess\repository;

use Illuminate\Support\Facades\App;

/**
 * Class RepositoryFactory
 * @package redhotmayo\dataaccess\repository
 * @author Craig Giles < craig@gilesc.com >
 *
 * @deprecated Use App::make() and get your references through the IoC container
 */
class RepositoryFactory {
    public static function GetSubscriptionRepository() {
        //for now, we're using SQL only
        return App::make('SubscriptionRepository');
    }

    public static function GetAccountRepository() {
        return App::make('AccountRepository');
    }

    public static function GetUserRepository() {
        return App::make('UserRepository');
    }
}
