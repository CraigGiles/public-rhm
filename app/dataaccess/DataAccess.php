<?php

/**
 * Class DataAccess
 * @package RedHotMayo\DataAccess
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class DataAccess {
    public static function GetSubscriptionDAO() {
        //currently we're just getting the SQL version
        return new SubscriptionSQL();
    }

    public static function GetAccountDAO() {
        return new AccountSQL();
    }
} 