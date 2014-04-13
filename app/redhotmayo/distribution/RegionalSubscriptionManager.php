<?php  namespace redhotmayo\distribution; 

use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\model\Subscription;
use redhotmayo\model\User;

class RegionalSubscriptionManager {
    const DATA_TYPE = 'type';
    const CITY = 'city';
    const STATE = 'state';
    const COUNTY = 'county';

    private $subscriptionRepository;
    private $zipcodeRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository,
        ZipcodeRepository $zipcodeRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->zipcodeRepository = $zipcodeRepository;
    }

    public function subscribeRegionsToUser(User $user, array $dataset) {
        //get all zipcodes for 'type' in state 'state'
        $zipcodes = $this->getZipcodesForRegions($dataset);

        foreach ($zipcodes as $zip) {
            $sub = new Subscription($user, $zip);
            $this->subscriptionRepository->save($sub);
        }
    }

    private function getZipcodesForRegions(array $dataset) {
        $zipcodes = [];

        foreach ($dataset as $data) {
            switch ($data[self::DATA_TYPE]) {
                case "city":
                    $zipcodes = array_merge($zipcodes, $this->getAllZipcodesForCity($data[self::CITY], $data[self::STATE]));
                    break;
                case "county":
                    $zipcodes = array_merge($zipcodes, $this->getAllZipcodesForCounty($data[self::COUNTY], $data[self::STATE]));
                    break;
            }
        }

        return $zipcodes;
    }

    private function getAllZipcodesForCity($city, $state) {
        return $this->zipcodeRepository->getZipcodesFromCity($city, $state);
    }

    private function getAllZipcodesForCounty($county, $state) {
        return $this->zipcodeRepository->getZipcodesFromCounty($county, $state);
    }
}
