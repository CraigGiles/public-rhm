<?php

use Illuminate\Support\Facades\Input;
use redhotmayo\dataaccess\repository\ZipcodeRepository;

class GeographyController extends \BaseController {
    /** @var ZipcodeRepository $zipcodeRepo */
    private $zipcodeRepo;

    function __construct(ZipcodeRepository $zipcodeRepo) {
        $this->zipcodeRepo = $zipcodeRepo;
    }

    public function search() {
        $data = [];
        $cities =  $this->zipcodeRepo->getAllCities(Input::all());
        $counties =  $this->zipcodeRepo->getAllCounties(Input::all());

        foreach ($cities as $city) {
            $value = [
                'city' => $city,
                'search_by' => $city,
                'type' => 'city',
            ];

            $data[] = $value;
        }

        foreach ($counties as $county) {
            $value = [
                'county' => $county,
                'search_by' => $county,
                'type' => 'county',
            ];

            $data[] = $value;
        }

        return $data;
    }
}
