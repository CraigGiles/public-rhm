<?php

/**
 * This class contains a list of all models that need to be stored in some sort of data storage
 *
 * Class DataAccessObject
 * @package RedHotMayo\DataAccess
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class DataAccessObject {
    public static function GetSubscriptionDAO() {
        $storage = DataAccessStorage::MYSQL; //TODO: later make this config base
        if ($storage === DataAccessStorage::MYSQL)
            return new SubscriptionSQL();
    }

    public static function GetAccountDAO() {
        $storage = DataAccessStorage::MYSQL; //TODO: later make this config based
        if ($storage === DataAccessStorage::MYSQL)
            return new AccountSQL();
    }
} 