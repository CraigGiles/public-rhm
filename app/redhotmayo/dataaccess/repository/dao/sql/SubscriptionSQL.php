<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use redhotmayo\model\Subscription;

class SubscriptionSQL extends DataAccessObjectSQL {
    const C_ID = 'id as subscriptionId';
    const C_USER_ID = 'userId';
    const C_ZIP_CODE = 'zipCode';
    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    public function getValues(Subscription $subscription) {
        return array(
            self::C_USER_ID => $subscription->getUserID(),
            self::C_ZIP_CODE => $subscription->getZipCode(),
            self::C_CREATED_AT => date('Y-m-d H:i:s'),
            self::C_UPDATED_AT => date('Y-m-d H:i:s'),
        );
    }

    /**
     * @param Subscription $subscription
     */
    public function save($subscription) {
        DB::beginTransaction();
        try {
            $values = $this->getValues($subscription);
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