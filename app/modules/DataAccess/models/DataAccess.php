<?php

/**
 * Class DataAccess
 * @package RedHotMayo\DataAccess
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class DataAccess {
    public static function getRepository($object, $database) {
        //if testing, use this other database
        //if in production, use $dat
    }

    public static function getDAO($object) {
        $storage = Config::get('database.default');

        //todo Later on this needs to be way more robust and load depending which storage we're using
        switch ($object) {
            case DataAccessObject::ACCOUNT:
                return new AccountSQL();

            case DataAccessObject::ADDRESS:
                return new AddressSQL();

            case DataAccessObject::CONTACT:
                throw new \Symfony\Component\Process\Exception\InvalidArgumentException("Not Implemented");

            case DataAccessObject::MOBILE_DEVICE:
                throw new \Symfony\Component\Process\Exception\InvalidArgumentException("Not Implemented");

            case DataAccessObject::NOTE:
                return new NoteSQL();

            case DataAccessObject::SUBSCRIPTION:
                return new SubscriptionSQL();

            case DataAccessObject::USER:
                return new AccountParse();

            default:
                throw new \InvalidArgumentException("Object " . $object . " not found in DataAccessObject.");
        }

    }

} 