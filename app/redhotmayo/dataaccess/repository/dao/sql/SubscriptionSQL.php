<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use redhotmayo\model\Subscription;

class SubscriptionSQL extends DataAccessObjectSQL {
    const TABLE_NAME = 'subscriptions';

    const C_ID = 'id as subscriptionId';
    const C_USER_ID = 'userId';
    const C_ZIP_CODE = 'zipCode';
    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    public static function GetColumns() {
        return [
            self::TABLE_NAME .'.'. self::C_ID,
            self::TABLE_NAME .'.'. self::C_USER_ID,
            self::TABLE_NAME .'.'. self::C_ZIP_CODE,
            self::TABLE_NAME .'.'. self::C_CREATED_AT,
            self::TABLE_NAME .'.'. self::C_UPDATED_AT,
        ];
    }

    public function getValues(Subscription $subscription) {
        return array(
            self::C_USER_ID => $subscription->getUserID(),
            self::C_ZIP_CODE => $subscription->getZipCode(),
            self::C_CREATED_AT => Carbon::now(),
            self::C_UPDATED_AT => Carbon::now(),
        );
    }

    /**
     * @param Subscription $subscription
     */
    public function save($subscription) {
        DB::beginTransaction();
        try {
            $values = $this->getValues($subscription);
            $id = DB::table(self::TABLE_NAME)
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