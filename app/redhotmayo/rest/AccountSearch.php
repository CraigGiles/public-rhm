<?php namespace redhotmayo\rest;

use HttpInvalidParamException;
use redhotmayo\dataaccess\repository\AccountRepository;
use redhotmayo\rest\exceptions\InvalidSearchException;

class AccountSearch {
    const DISTANCE = 'distance';
    const SEARCH = 'search';

    const SORT = 'sort';
    const ASC = 'asc';
    const DESC = 'desc';

    const OPTIONAL = 'optional';
    const REQUIRED = 'required';

    const TOKEN = 'token';
    const TARGETED = 'targeted';
    const ACCOUNT_NAME = 'accountName';
    const LAT = 'lat';
    const LON = 'lon';
    const ZIPCODE = 'zipcode';
    const CREATION_DATE = 'creationDate';
    const UPDATED_DATE = 'updatedDate';
    const OPEN_DATE = 'openDate';
    const WEEKLY_OPPORTUNITY = 'weeklyOpportunity';
    const SORT_ORDER = 'order';

    private $params;

    public function __construct(AccountRepository $repo, SearchParameters $params) {
        $this->accountRepo = $repo;
        $this->params = $params;
    }

    public function execute() {
        $searchType = $this->params->getSearch();
        switch ($searchType) {
            case self::SEARCH:
                return $this->search();

            case self::DISTANCE:
                return $this->distance();

            default:
                throw new InvalidSearchException("{$searchType} is not a valid search");
        }
    }

    private function search() {
        $results = array();
        $accountName = null;
        $targeted = null;
        $zipcode = null;
        $sortMethod = null;
        $sortOrder = self::DESC;

        $constraints = $this->params->getConstraints();
        foreach ($constraints as $constraint => $value) {
            switch($constraint) {
                case self::ACCOUNT_NAME:
                    $accountName = $value;
                    break;

                case self::TARGETED:
                    $targeted = $value;
                    break;

                case self::ZIPCODE:
                    $zipcode = $value;
                    break;

                case self::SORT:
                    $sortMethod = $this->getSortMethod($value);
                    break;
            }
        }


        $constraints = array(
            self::ACCOUNT_NAME => $accountName,
            self::TARGETED => $targeted,
            self::ZIPCODE => $zipcode,
            self::SORT => $sortMethod,
            self::SORT_ORDER => $sortOrder,
        );

        $results = $this->accountRepo->find($constraints);

        return $results;
    }

    private function distance() {
        $results = array();



        return $results;
    }

    private function getSortMethod($value) {
        switch ($value) {
            case self::CREATION_DATE:
                return $value;

            case self::UPDATED_DATE:
                return $value;

            case self::OPEN_DATE:
                return $value;

            case self::WEEKLY_OPPORTUNITY:
                return $value;

            default:
                throw new HttpInvalidParamException("{$value} is not a valid search parameter");
        }
    }
}