<?php

class AccountParserS2 {
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

    private $addrStandardization;
    private $cassVerification;
    private $geocoder;

    public function __construct(AddressStandardizationService $addrStandardization,
                                CassVerificationService $cassVerification,
                                Geocoder $geocoder) {
        if (!isset($addrStandardization) || !isset($cassVerification) || !isset($geocoder)) {
            throw new InvalidArgumentException("Invalid address processor detected");
        }

        $this->addrStandardization = $addrStandardization;
        $this->cassVerification = $cassVerification;
        $this->geocoder = $geocoder;

    }

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

            $note = new Note();
            $note->setText($account[self::C_DESCRIBE]);
            $note->setAuthor(self::C_AUTHOR);
            $note->setAction(self::C_ACTION);

            //do address checking here
            $addressParser = new AddressParserS2();
            $address = $addressParser->processAddressInformation($account, $this->addrStandardization, $this->cassVerification, $this->geocoder);
            $acc->setAddress($address);

            // calculate the weekly opportunity
            $acc->calculateWeightedOpportunity();

            $return[] = $acc;

            if (count($return) > 25) {
                break;
            }
        }

        return $return;
    }
}