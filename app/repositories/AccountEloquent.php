<?php

class AccountEloquent extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    public function setAttributes($account) {
        $this->userId = $account->getUserID();
        $this->weeklyOpportunity = $account->getWeeklyOpportunity();
//        $this->estimatedAnnualSales = $account->getEstimatedAnnualSales();
//        $this->owner = $account->getOwner();
//        $this->mobilePhone = $account->getMobilePhone();
//        $this->websiteAddress = $account->getWebsiteAddress();
//        $this->isTargetAccount = $account->getIsTargetAccount();
        $this->accountName = $account->getAccountName();
        $this->operatorType= $account->getOperatorType();
        $this->addressId = $account->getAddress();
        $this->contactName = $account->getContactName();
        $this->phone = $account->getPhone();
        $this->serviceType = $account->getServiceType();
        $this->cuisineType = $account->getCuisineType();
        $this->seatCount = $account->getSeatCount();
//        $this->averageCheck = $account->getAverageCheck();
        $this->emailAddress = $account->getEmailAddress();
        $this->openDate = $account->getOpenDate();

    }

//    /**
//     * The attributes excluded from the model's JSON form.
//     *
//     * @var array
//     */
//    protected $hidden = array('password');


} 