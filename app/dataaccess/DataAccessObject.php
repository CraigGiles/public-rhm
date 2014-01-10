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
        //currently we're just getting the SQL version
        return new SubscriptionSQL();
    }

    public static function GetAccountDAO() {
        return new AccountSQL();
    }
} 