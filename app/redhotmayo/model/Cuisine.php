<?php namespace redhotmayo\model;

class Cuisine extends DataObject {
    private $meta;
    private $cuisine;

    function __construct($cuisine=null, $meta=null) {
        $this->meta = $meta;
        $this->cuisine = $cuisine;
    }

    public function getCuisineId() {
        return $this->getId();
    }

    public function setCuisineId($id) {
        $this->setId($id);
    }

    /**
     * @param mixed $cuisine
     */
    public function setCuisine($cuisine) {
        $this->cuisine = $cuisine;
    }

    /**
     * @return mixed
     */
    public function getCuisine() {
        return $this->cuisine;
    }

    /**
     * @param mixed $meta
     */
    public function setMeta($meta) {
        $this->meta = $meta;
    }

    /**
     * @return mixed
     */
    public function getMeta() {
        return $this->meta;
    }


}