<?php namespace redhotmayo\dataaccess\repository\sql;


use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use redhotmayo\dataaccess\encryption\EncryptedSQLTable;
use redhotmayo\dataaccess\repository\Repository;

abstract class RepositorySQL implements Repository {
    abstract public function getTableName();
    abstract public function getColumns();
    abstract protected function getConstraints($parameters);

    /**
     * Prepares all values returned from the database to a format which can be
     * consumed by the application. Encrypted values will be unencrypted
     * prior to conversion.
     *
     * @param $values
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    abstract protected function filter(array $values);

    /**
     * Return an array of all objects that match the given constraints
     *
     * @param $parameters
     *
     * @return array
     *
     * @throws Exception
     */
    public function find($parameters) {
        try {
            $constraints = $this->getConstraints($parameters);

            $builder = DB::table($this->getTableName())
                         ->select($this->getColumns());

            foreach($constraints as $constraint => $value) {
                $builder->where($constraint, '=', $value);
            }

            $results = $builder->get();
            return $this->filter(json_decode(json_encode($results), true));
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $object
     *
     * @return bool
     *
     * @throws Exception
     */
    public function save($object) {
        try {
            $id = DB::table($this->getTableName())
                    ->insertGetId($this->getValues());

            $object->setId($id);

            return isset($id) && is_numeric($id) && $id > 0;
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll(array $objects) {
        foreach ($objects as $object) {
            $this->save($object);
        }
    }
}
