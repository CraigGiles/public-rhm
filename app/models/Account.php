<?php

/**
 * Class Account
 * @package RedHotMayo\Models
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class Account extends DataObject {
    private $userId;
    private $weeklyOpportunity;
    private $estimatedAnnualSales;
    private $owner;
    private $mobilePhone;
    private $websiteAddress;
    private $isTargetAccount;
    private $accountName;
    private $operatorType;
    private $address;
    private $contactName;
    private $phone;
    private $serviceType;
    private $cuisineType;
    private $seatCount;
    private $averageCheck;
    private $emailAddress;
    private $openDate;
    private $notes;
    private $isMaster;

    /**
     * Calculates the weighted opportunity of this lead
     */
    public function calculateWeightedOpportunity() {
        $val = rand(0, 10);
        $this->weeklyOpportunity = $val ;
    }

    public function addNotes(array $notes) {
        foreach ($notes as $note) {
            $this->addNote($note);
        }
    }

    public function addNote(Note $note) {
        $this->notes[] = $note;
    }

    /**
     * @param mixed $accountName
     */
    public function setAccountName($accountName) {
        $this->accountName = $accountName;
    }

    /**
     * @return mixed
     */
    public function getAccountName() {
        return $this->accountName;
    }


    /**
     * @param Address $address
     */
    public function setAddress($address) {
        if ($address instanceof Address) {
            $this->address = $address;
        }
    }

    /**
     * @return Address
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param mixed $averageCheck
     */
    public function setAverageCheck($averageCheck) {
        $this->averageCheck = $averageCheck;
    }

    /**
     * @return mixed
     */
    public function getAverageCheck() {
        return $this->averageCheck;
    }

    /**
     * @param mixed $contactName
     */
    public function setContactName($contactName) {
        $this->contactName = $contactName;
    }

    /**
     * @return mixed
     */
    public function getContactName() {
        return $this->contactName;
    }

    /**
     * @param mixed $cuisineType
     */
    public function setCuisineType($cuisineType) {
        $this->cuisineType = $cuisineType;
    }

    /**
     * @return mixed
     */
    public function getCuisineType() {
        return $this->cuisineType;
    }

    /**
     * @param mixed $emailAddress
     */
    public function setEmailAddress($emailAddress) {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress() {
        return $this->emailAddress;
    }

    /**
     * @param mixed $estimatedAnnualSales
     */
    public function setEstimatedAnnualSales($estimatedAnnualSales) {
        $this->estimatedAnnualSales = $estimatedAnnualSales;
    }

    /**
     * @return mixed
     */
    public function getEstimatedAnnualSales() {
        return $this->estimatedAnnualSales;
    }

    /**
     * @param mixed $isTargetAccount
     */
    public function setIsTargetAccount($isTargetAccount) {
        $this->isTargetAccount = $isTargetAccount;
    }

    /**
     * @return mixed
     */
    public function getIsTargetAccount() {
        return $this->isTargetAccount;
    }

    /**
     * @param mixed $mobilePhone
     */
    public function setMobilePhone($mobilePhone) {
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * @return mixed
     */
    public function getMobilePhone() {
        return $this->mobilePhone;
    }

    /**
     * @param mixed $operatorType
     */
    public function setOperatorType($operatorType) {
        $this->operatorType = $operatorType;
    }

    /**
     * @return mixed
     */
    public function getOperatorType() {
        return $this->operatorType;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner) {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getOwner() {
        return $this->owner;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone) {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param mixed $seatCount
     */
    public function setSeatCount($seatCount) {
        $this->seatCount = $seatCount;
    }

    /**
     * @return mixed
     */
    public function getSeatCount() {
        return $this->seatCount;
    }

    /**
     * @param mixed $serviceType
     */
    public function setServiceType($serviceType) {
        $this->serviceType = $serviceType;
    }

    /**
     * @return mixed
     */
    public function getServiceType() {
        return $this->serviceType;
    }

    /**
     * @param mixed $websiteAddress
     */
    public function setWebsiteAddress($websiteAddress) {
        $this->websiteAddress = $websiteAddress;
    }

    /**
     * @return mixed
     */
    public function getWebsiteAddress() {
        return $this->websiteAddress;
    }

    /**
     * @param mixed $weeklyOpportunity
     */
    public function setWeeklyOpportunity($weeklyOpportunity) {
        $this->weeklyOpportunity = $weeklyOpportunity;
    }

    /**
     * @return mixed
     */
    public function getWeeklyOpportunity() {
        return $this->weeklyOpportunity;
    }

    /**
     * @return mixed
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * @param mixed $openDate
     */
    public function setOpenDate($openDate) {
        $this->openDate = $openDate;
    }

    /**
     * @return mixed
     */
    public function getOpenDate() {
        return $this->openDate;
    }

    /**
     * @return mixed
     */
    public function getUserID() {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserID($userId) {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getIsMaster() {
        return $this->isMaster;
    }

    /**
     * @param mixed $isMaster
     */
    public function setIsMaster($isMaster) {
        $this->isMaster = $isMaster;
    }


}