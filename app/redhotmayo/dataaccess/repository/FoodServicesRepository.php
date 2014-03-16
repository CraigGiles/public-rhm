<?php namespace redhotmayo\dataaccess\repository;


use redhotmayo\model\Service;

interface FoodServicesRepository {
    /**
     * Map a given service to a specified service type
     *
     * @param $source
     * @param $service
     * @return Service
     */
    public function map($source, $service);
} 