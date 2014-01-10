<?php


abstract class AccountParser {
    protected $addrStandardization;
    protected $cassVerification;
    protected $geocoder;

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

    public abstract function processAccounts($accounts);
} 