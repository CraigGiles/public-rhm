<?php namespace redhotmayo\dataaccess\repository\dao;

use redhotmayo\model\Address;

interface AddressDAO {
    /**
     * Save a record and return the objectId
     *
     * @param Address $address
     * @return mixed
     */
    public function save(Address $address);

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $addresses
     * @return array
     */
    public function saveAll(array $addresses);
}
