<?php namespace redhotmayo\parser;

use Illuminate\Support\Facades\App;
use redhotmayo\dataaccess\repository\CuisineRepository;
use redhotmayo\dataaccess\repository\FoodServicesRepository;
use redhotmayo\model\Account;
use redhotmayo\model\Note;

class AccountParserS2 extends AccountParser {
    const EXCEL_PROCESSOR = 'processAccounts';
    const C_OPERATION = 'OPERATION';
    const C_NAME = 'NAME';
    const C_LASTNAME = 'LASTNAME';
    const C_PHONE = 'PHONE';
    const C_EMAIL = 'EMAIL';
    const C_NOSEATS = 'NOSEATS';
    const C_SERVICETYPE = 'SERVICETYPE';
    const C_MENU = 'MENU';
    const C_CATEGORY = 'CATEGORY';
    const C_OPENDATE = 'OPENDATE';
    const C_DESCRIBE = 'DESCRIBE';
    const C_AUTHOR = 'redhotMAYO';
    const C_ACTION = 'RHM Import';
    const C_OPERATOR_SIZE = 'CATEGORY2';
    const C_ALCOHOL_SERVICE = 'ALCOHOL';
    const C_OPERATOR_STATUS = 'STATUS';
    const C_CONTACT_TITLE = 'CONTACT';
    const C_MEAL_PERIOD = 'SERVICE';
    const C_WEBSITE = 'SOURCE';
    const C_CHECK_AVERAGE = 'CHECKAVERAGE';

    private $averageCheckParser;

    public function processAccounts($accounts) {
        $return = array();

        foreach ($accounts as $account) {
            $acc = new Account();

            $acc->setAccountName($account[self::C_OPERATION]);
            $acc->setContactName($account[self::C_NAME] . " " . $account[self::C_LASTNAME]);
            $acc->setPhone($account[self::C_PHONE]);
            $acc->setEmailAddress($account[self::C_EMAIL]);
            $acc->setSeatCount($account[self::C_NOSEATS]);
            $acc->setServiceType($account[self::C_SERVICETYPE]);
            $acc->setCuisineType($account[self::C_MENU]);
            $acc->setOperatorType($account[self::C_CATEGORY]);
            $acc->setOpenDate($account[self::C_OPENDATE]);

            $operatorSize = isset($account[self::C_OPERATOR_SIZE]) ? $account[self::C_OPERATOR_SIZE] : null;
            $alcoholService = isset($account[self::C_ALCOHOL_SERVICE]) ? $account[self::C_ALCOHOL_SERVICE] : null;
            $operatorStatus = isset($account[self::C_OPERATOR_STATUS]) ? $account[self::C_OPERATOR_STATUS] : null;
            $contactTitle = isset($account[self::C_CONTACT_TITLE]) ? $account[self::C_CONTACT_TITLE] : null;
            $mealPeriod = isset($account[self::C_MEAL_PERIOD]) ? $account[self::C_MEAL_PERIOD] : null;
            $website = isset($account[self::C_WEBSITE]) ? $account[self::C_WEBSITE] : null;
            $checkAverage = isset($account[self::C_CHECK_AVERAGE]) ? $account[self::C_CHECK_AVERAGE] : null;

            $acc->setOperatorSize($operatorSize);
            $acc->setAlcoholService($alcoholService);
            $acc->setOperatorStatus($operatorStatus);
            $acc->setContactTitle($contactTitle);
            $acc->setMealPeriod($mealPeriod);
            $acc->setWebsite($website);

            $aveCheckParser = $this->getAverageCheckParser();
            $average = $aveCheckParser->parse($checkAverage);
            $acc->setAverageCheck($average);

            $note = new Note();
            $note->setText($account[self::C_DESCRIBE]);
            $note->setAuthor(self::C_AUTHOR);
            $note->setAction(self::C_ACTION);
            $acc->addNote($note);

            //do address checking here
            $addressParser = new AddressParserS2();
            $address = $addressParser->processAddressInformation($this->addrStandardization, $this->cassVerification, $this->geocoder, $account);
            $acc->setAddress($address);

            /** @var CuisineRepository $cuisineRepo */
            $cuisineRepo = App::make('redhotmayo\dataaccess\repository\CuisineRepository');

            /** @var FoodServicesRepository $serviceRepo */
            $serviceRepo = App::make('redhotmayo\dataaccess\repository\FoodServicesRepository');

            $type = $cuisineRepo->map('s2', $acc->getCuisineType());
            $acc->setCuisineType($type->getCuisine());
            $acc->setCuisineId($type->getCuisineId());

            $type = $serviceRepo->map('s2', $acc->getServiceType());
            $acc->setServiceType($type->getService());
            $acc->setServiceId($type->getServiceId());

            // calculate the weekly opportunity
            $acc->calculateWeeklyOpportunity();

            $return[] = $acc;
            break;
        }
        return $return;
    }

    /**
     * @return AverageCheck
     */
    public function getAverageCheckParser() {
        if (!isset($this->averageCheckParser)) {
            $this->averageCheckParser = new AverageCheck();
        }

        return $this->averageCheckParser;
    }

    public function setAverageCheckParser(AverageCheck $parser) {
        $this->averageCheckParser = $parser;
    }
}