<?php

class SubscriptionSQL extends DataAccessObjectSQL {
    const USER_ID = 'userId';
    const ZIP_CODE = 'zipCode';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function getValues(Subscription $subscription) {
        return array(
            self::USER_ID => $subscription->getUserID(),
            self::ZIP_CODE => $subscription->getZipCode(),
            self::CREATED_AT => new DateTime,
            self::UPDATED_AT => new DateTime,
        );
    }

    /**
     * @param Subscription $subscription
     */
    public function save($subscription) {
        DB::beginTransaction();
        $values = $this->getValues($subscription);
        try {
            $id = DB::table('subscriptions')
                           ->insertGetId($values);

            DB::commit();
            return $id;
        } catch (Exception $e) {
            //todo: Log Exception
            DB::rollBack();
            return null;
        }
    }

}