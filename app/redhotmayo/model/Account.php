<?php namespace redhotmayo\model;

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
    private $operatorSize;
    private $operatorStatus;
    private $address;
    private $contactTitle;
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
    private $cuisineId;
    private $alcoholService;
    private $mealPeriod;

    public static function FromArray($account) {
        return Account::FromStdClass(json_decode(json_encode($account)));
    }

    public static function FromStdClass($account) {
        $userId = isset($account->userId) ? $account->userId : null;
        $weeklyOpportunity = isset($account->weeklyOpportunity) ? $account->weeklyOpportunity : null;
        $estimatedAnnualSales = isset($account->estimatedAnnualSales) ? $account->estimatedAnnualSales : null;
        $owner = isset($account->owner) ? $account->owner : null;
        $mobilePhone = isset($account->mobilePhone) ? $account->mobilePhone : null;
        $websiteAddress = isset($account->websiteAddress) ? $account->websiteAddress : null;
        $isTargetAccount = isset($account->isTargetAccount) ? $account->isTargetAccount : null;
        $accountName = isset($account->accountName) ? $account->accountName : null;
        $operatorType = isset($account->operatorType) ? $account->operatorType : null;
        $contactName = isset($account->contactName) ? $account->contactName : null;
        $phone = isset($account->phone) ? $account->phone : null;
        $serviceType = isset($account->serviceType) ? $account->serviceType : null;
        $cuisineType = isset($account->cuisineType) ? $account->cuisineType : null;
        $seatCount = isset($account->seatCount) ? $account->seatCount : null;
        $averageCheck = isset($account->averageCheck) ? $account->averageCheck : null;
        $emailAddress = isset($account->emailAddress) ? $account->emailAddress : null;
        $openDate = isset($account->openDate) ? $account->openDate : null;
        $isMaster = isset($account->isMaster) ? $account->isMaster : null;
        $id = isset($account->id) ? $account->id : null;
        $cuisineId = isset($account->cuisineId) ? $account->cuisineId : null;

        $address = isset($account->address) ? $account->address : null;
        $notes = isset($account->notes) ? $account->notes : null;

        if (isset($address) && $address instanceof \stdClass) {
            $address = Address::create($address);
        }

        $obj = new Account();
        $obj->setUserID($userId);
        $obj->setWeeklyOpportunity($weeklyOpportunity);
        $obj->setEstimatedAnnualSales($estimatedAnnualSales);
        $obj->setOwner($owner);
        $obj->setMobilePhone($mobilePhone);
        $obj->setWebsite($websiteAddress);
        $obj->setIsTargetAccount($isTargetAccount);
        $obj->setAccountName($accountName);
        $obj->setOperatorType($operatorType);
        $obj->setContactName($contactName);
        $obj->setPhone($phone);
        $obj->setServiceType($serviceType);
        $obj->setCuisineType($cuisineType);
        $obj->setSeatCount($seatCount);
        $obj->setAverageCheck($averageCheck);
        $obj->setEmailAddress($emailAddress);
        $obj->setOpenDate($openDate);
        $obj->setIsMaster($isMaster);
        $obj->setAccountId($id);
        $obj->setCuisineId($cuisineId);

        $obj->setAddress($address);

        if (isset($notes) && count($notes) > 0) {
            foreach ($notes as $note) {
                if ($note instanceof \stdClass) {
                    $obj->addNote(Note::create($note));
                }
            }
        }
        //todo: notes are not in this list
        //todo: address isn't in this list
        return $obj;
    }

    public function getAccountId() {
        return $this->getId();
    }

    public function setAccountId($id) {
        $this->setId($id);
    }

    /**
     * Calculates the weighted opportunity of this lead
     */
    public function calculateWeeklyOpportunity() {
        $val = rand(0, 10);
        $this->weeklyOpportunity = $val;
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
        $this->isTargetAccount = (bool)$isTargetAccount;
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
    public function setWebsite($websiteAddress) {
        $this->websiteAddress = $websiteAddress;
    }

    /**
     * @return mixed
     */
    public function getWebsite() {
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
        $this->isMaster = (bool)$isMaster;
    }

    /**
     * @param mixed $cuisineId
     */
    public function setCuisineId($cuisineId) {
        $this->cuisineId = $cuisineId;
    }

    /**
     * @return mixed
     */
    public function getCuisineId() {
        return $this->cuisineId;
    }

    /**
     * @param mixed $alcoholService
     */
    public function setAlcoholService($alcoholService) {
        $this->alcoholService = $alcoholService;
    }

    /**
     * @return mixed
     */
    public function getAlcoholService() {
        return $this->alcoholService;
    }

    /**
     * @param mixed $contactTitle
     */
    public function setContactTitle($contactTitle) {
        $this->contactTitle = $contactTitle;
    }

    /**
     * @return mixed
     */
    public function getContactTitle() {
        return $this->contactTitle;
    }

    /**
     * @param mixed $mealPeriod
     */
    public function setMealPeriod($mealPeriod) {
        $this->mealPeriod = $mealPeriod;
    }

    /**
     * @return mixed
     */
    public function getMealPeriod() {
        return $this->mealPeriod;
    }

    /**
     * @param mixed $operatorSize
     */
    public function setOperatorSize($operatorSize) {
        $this->operatorSize = $operatorSize;
    }

    /**
     * @return mixed
     */
    public function getOperatorSize() {
        return $this->operatorSize;
    }

    /**
     * @param mixed $operatorStatus
     */
    public function setOperatorStatus($operatorStatus) {
        $this->operatorStatus = $operatorStatus;
    }

    /**
     * @return mixed
     */
    public function getOperatorStatus() {
        return $this->operatorStatus;
    }


    public function toArray() {
        $json = (
        array(
            'userId' => $this->userId,
            'weeklyOpportunity' => $this->weeklyOpportunity,
            'estimatedAnnualSales' => $this->estimatedAnnualSales,
            'owner' => $this->owner,
            'mobilePhone' => $this->mobilePhone,
            'websiteAddress' => $this->websiteAddress,
            'isTargetAccount' => $this->isTargetAccount,
            'accountName' => $this->accountName,
            'operatorType' => $this->operatorType,
            'address' => $this->address->toArray(),
            'contactName' => $this->contactName,
            'phone' => $this->phone,
            'serviceType' => $this->serviceType,
            'cuisineType' => $this->cuisineType,
            'seatCount' => $this->seatCount,
            'averageCheck' => $this->averageCheck,
            'emailAddress' => $this->emailAddress,
            'openDate' => $this->openDate,
            'notes' => $this->notes[0]->toArray(),
            'isMaster' => $this->isMaster,
        )
        );
        return $json;
    }
}