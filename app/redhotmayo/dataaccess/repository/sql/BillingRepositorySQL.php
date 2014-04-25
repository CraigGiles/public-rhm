<?php namespace redhotmayo\dataaccess\repository\sql;

use redhotmayo\dataaccess\repository\BillingRepository;
use redhotmayo\dataaccess\repository\dao\sql\BillingSQL;

class BillingRepositorySQL extends RepositorySQL implements BillingRepository {
    const SERVICE = 'redhotmayo\dataaccess\repository\sql\BillingRepositorySQL';

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $object
     * @return bool
     */
    public function save($object) {
        $dao = new BillingSQL();//todo: dont do this
        $dao->save($object);
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll(array $objects) {
        // TODO: Implement saveAll() method.
    }

    public function getTableName() {
        return BillingSQL::TABLE_NAME;
    }

    public function getColumns() {

    }

    protected function getConstraints($parameters) {

    }
}
