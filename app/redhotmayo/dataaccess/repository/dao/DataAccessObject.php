<?php namespace redhotmayo\dataaccess\repository\dao;
use redhotmayo\dataaccess\DataAccessStorage;
use redhotmayo\dataaccess\repository\dao\sql\AccountSQL;
use redhotmayo\dataaccess\repository\dao\sql\AddressSQL;
use redhotmayo\dataaccess\repository\dao\sql\NoteSQL;
use redhotmayo\dataaccess\repository\dao\sql\SubscriptionSQL;
use redhotmayo\dataaccess\repository\dao\sql\UserSQL;

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

    public static function GetAddressDAO() {
        $storage = DataAccessStorage::MYSQL; //TODO: later make this config based
        if ($storage === DataAccessStorage::MYSQL)
            return new AddressSQL();
    }

    public static function GetNoteDAO() {
        $storage = DataAccessStorage::MYSQL; //TODO: later make this config based
        if ($storage === DataAccessStorage::MYSQL)
            return new NoteSQL();
    }

    public static function GetUserDAO() {
        $storage = DataAccessStorage::MYSQL; //TODO: later make this config based
        if ($storage === DataAccessStorage::MYSQL)
            return new UserSQL();
    }
} 