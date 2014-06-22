<?php namespace redhotmayo\model;

abstract class DataObject {
    const ID = 'id';

    private $id;

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }
}
