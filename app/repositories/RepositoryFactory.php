<?php

class RepositoryFactory {
    public static function GetSubscriptionRepository() {
        //for now, we're using SQL only
        return new SubscriptionRepositorySQL();
    }

    public static function GetAccountRepository() {
        return new AccountRepositorySQL();
    }
}