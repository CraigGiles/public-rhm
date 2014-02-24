<?php namespace redhotmayo\library;

use redhotmayo\dataaccess\repository\CuisineRepository;

class CuisineMapper {
    const DEFAULT_CUISINE = 'OTHER';

    protected $cuisineRepo;

    public function __construct(CuisineRepository $cuisineRepo) {
        $this->cuisineRepo = $cuisineRepo;
    }

    public function mapCuisine($source, $cuisine) {
        return $this->cuisineRepo->map($source, $cuisine);
    }
}