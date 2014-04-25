<?php

use Illuminate\Support\Facades\Input;
use redhotmayo\dataaccess\repository\ZipcodeRepository;

class GeographyController extends RedHotMayoWebController {
    /** @var ZipcodeRepository $zipcodeRepo */
    private $zipcodeRepo;

    function __construct(ZipcodeRepository $zipcodeRepo) {
        $this->zipcodeRepo = $zipcodeRepo;
    }

    public function search() {
        $data = [];
        $cities =  $this->zipcodeRepo->getAllCities(Input::all());
        $counties =  $this->zipcodeRepo->getAllCounties(Input::all());

        $fe_id = 0;
        foreach ($cities as $city) {
            $value = [
                'id' => $fe_id,
                'city' => $city,
                'search_by' => $city,
                'type' => 'city',
            ];

            $data[] = $value;
            $fe_id++;
        }

        foreach ($counties as $county) {
            $value = [
                'id' => $fe_id,
                'county' => $county,
                'search_by' => $county,
                'type' => 'county',
            ];

            $data[] = $value;
            $fe_id++;
        }

        return $data;
    }
}
