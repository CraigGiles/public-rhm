<?php namespace redhotmayo\dataaccess\repository\sql;


use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\FoodServicesRepository;
use redhotmayo\model\Service;

class FoodServicesRepositorySQL implements FoodServicesRepository {
    const SERVICE_TABLE = 'food_services';
    const SERVICE = 'redhotmayo\dataaccess\repository\sql\FoodServicesRepositorySQL';

    private $validSources = [ 's2' ];

    public function map($source, $service) {
        if (in_array($source, $this->validSources)) {
            $data = (array)DB::table(self::SERVICE_TABLE)
                             ->where($source, '=', $service)
                             ->first();

            $id = isset($data['id']) ? $data['id'] : null;
            $service = isset($data['service']) ? $data['service'] : null;
            $meta = isset($data['meta']) ? $data['meta'] : null;

            $service = new Service($service, $meta);
            $service->setServiceId($id);
            return $service;
        } else {
            throw new \InvalidArgumentException("{$source} is not a valid service source.");
        }
    }
}