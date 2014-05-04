<?php  namespace redhotmayo\model;

use redhotmayo\utility\Arrays;

class Billing extends DataObject {
    const USER_ID = 'userId';
    const PLAN_ID = 'planId';
    const CUSTOMER_TOKEN = 'customerToken';
    const SUBSCRIPTION_ENDS_AT = 'subscriptionEndsAt';

    private $userId;
    private $planId;
    private $customerToken;
    private $subscriptionEndsAt;

    function __construct($userId, $planId, $customerToken, $subscriptionEndsAt) {
        $this->userId;
        $this->planId = $planId;
        $this->customerToken = $customerToken;
        $this->subscriptionEndsAt = $subscriptionEndsAt;
    }

    public static function createWithData($array) {
        return new self(
            Arrays::GetValue($array, self::USER_ID, null),
            Arrays::GetValue($array, self::PLAN_ID, null),
            Arrays::GetValue($array, self::CUSTOMER_TOKEN, null),
            Arrays::GetValue($array, self::SUBSCRIPTION_ENDS_AT, null)
        );
    }

    public function setSubscriptionEndsAt($currentPeriodEnd) {
        $this->subscriptionEndsAt = $currentPeriodEnd;
    }

    public function getSubscriptionEndsAt() {
        return $this->subscriptionEndsAt;
    }

    public function setCustomerToken($customerToken) {
        $this->customerToken = $customerToken;
    }

    public function getCustomerToken() {
        return $this->customerToken;
    }

    public function setPlanId($plan) {
        $this->planId = (int)$plan;
    }

    public function getPlanId() {
        return $this->planId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getUserId() {
        return $this->userId;
    }
}
