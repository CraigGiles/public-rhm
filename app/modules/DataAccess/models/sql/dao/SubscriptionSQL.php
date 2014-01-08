<?php

class SubscriptionSQL extends DataAccessObjectSQL {
    const USER_ID = 'userId';
    const ZIP_CODE = 'zipCode';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function getValues(Subscription $subscription) {
        return array(
            self::USER_ID => $subscription->getUser(),
            self::ZIP_CODE => $subscription->getZipCode(),
            self::CREATED_AT => new DateTime,
            self::UPDATED_AT => new DateTime,
        );
    }

    /**
     * @param Subscription $subscription
     */
    public function save($subscription) {
        $this->db->beginTransaction();
        $values = $this->getValues($subscription);
        try {
            $id = $this->db->table('subscriptions')
                           ->insertGetId($values);

            $this->db->commit();
            return $id;
        } catch (Exception $e) {
            //todo: Log Exception
            $this->db->rollBack();
            return null;
        }
    }

}