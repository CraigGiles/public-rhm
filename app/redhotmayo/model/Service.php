<?php namespace redhotmayo\model;


class Service extends DataObject {
    private $meta;
    private $service;

    function __construct($service=null, $meta=null) {
        $this->meta = $meta;
        $this->service = $service;
    }

    public function getServiceId() {
        return $this->getId();
    }

    public function setServiceId($id) {
        $this->setId($id);
    }

    /**
     * @param mixed $service
     */
    public function setService($service) {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getService() {
        return $this->service;
    }

    /**
     * @param string $meta
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