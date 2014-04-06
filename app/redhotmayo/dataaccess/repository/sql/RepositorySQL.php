<?php namespace redhotmayo\dataaccess\repository\sql;


use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\Repository;

abstract class RepositorySQL implements Repository {
    abstract public function getTableName();
    abstract public function getColumns();
    abstract protected function getConstraints($parameters);

    /**
     * Return an array of all objects
     *
     * @return array
     */
    public function all() {
        // TODO: Implement all() method.
    }

    /**
     * Return an array of all objects that match the given constraints
     *
     * @param $parameters
     * @return mixed
     */
    public function find($parameters) {
        $constraints = $this->getConstraints($parameters);

        $builder = DB::table($this->getTableName())
                     ->select($this->getColumns());

        foreach($constraints as $constraint => $value) {
            $builder->where($constraint, '=', $value);
        }

        return $builder->get();
    }

    /**
     * Create an object from given input
     *
     * @param $input
     * @return mixed
     */
    public function create($input) {
        // TODO: Implement create() method.
    }

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $object
     * @return bool
     */
    public function save($object) {
        // TODO: Implement save() method.
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll($objects) {
        // TODO: Implement saveAll() method.
    }
}