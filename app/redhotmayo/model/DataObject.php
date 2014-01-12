<?php namespace redhotmayo\model;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class DataObject {
    private $id;

    /**
     * @param mixed $id
     */
    protected function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    protected function getId() {
        return $this->id;
    }

    /**
     * Convert object to json string
     *
     * @return string|\Symfony\Component\Serializer\Encoder\scalar
     */
    public function toJson() {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->serialize($this, 'json');
    }
}