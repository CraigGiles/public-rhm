<?php namespace redhotmayo\dataaccess\repository;

use redhotmayo\dataaccess\repository\sql\AccountRepositorySQL;
use redhotmayo\dataaccess\repository\sql\SubscriptionRepositorySQL;

class RepositoryFactory {
    public static function GetSubscriptionRepository() {
        //for now, we're using SQL only
        return new SubscriptionRepositorySQL();
    }

    public static function GetAccountRepository() {
        return new AccountRepositorySQL();
    }
}