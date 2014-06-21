<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use redhotmayo\billing\Subscription;
use redhotmayo\dataaccess\encryption\EncryptedSQLTable;
use redhotmayo\dataaccess\repository\dao\BillingStripeDAO;

class BillingStripeSQL extends EncryptedSQLTable implements BillingStripeDAO {
    const TABLE_NAME = 'billing_stripe';

    const C_ID                   = 'id';
    const C_PLAN_ID              = 'plan_id';
    const C_STATUS               = 'current_status';
    const C_CUSTOMER_TOKEN       = 'customer_token';
    const C_SUBSCRIPTION_ENDS_AT = 'subscription_ends_at';
    const C_AUTO_RENEW           = 'auto_renew';
    const C_TRIAL_END            = 'trial_ends_at';
    const C_CANCELED_AT          = 'canceled_at';
    const C_CREATED_AT           = 'created_at';
    const C_UPDATED_AT           = 'updated_at';
    const C_UPGRADED_AT          = 'upgraded_at';
    const C_UPGRADED_ID          = 'upgraded_id';

    const UNKNOWN_CUSTOMER_TOKEN = '';

    public static function GetColumns() {
        return [
            self::TABLE_NAME .'.'. self::C_ID,
            self::TABLE_NAME .'.'. self::C_PLAN_ID,
            self::TABLE_NAME .'.'. self::C_STATUS,
            self::TABLE_NAME .'.'. self::C_CUSTOMER_TOKEN,
            self::TABLE_NAME .'.'. self::C_SUBSCRIPTION_ENDS_AT,
            self::TABLE_NAME .'.'. self::C_AUTO_RENEW,
            self::TABLE_NAME .'.'. self::C_TRIAL_END,
            self::TABLE_NAME .'.'. self::C_CANCELED_AT,
            self::TABLE_NAME .'.'. self::C_CREATED_AT,
            self::TABLE_NAME .'.'. self::C_UPDATED_AT,
            self::TABLE_NAME .'.'. self::C_UPGRADED_AT,
            self::TABLE_NAME .'.'. self::C_UPGRADED_ID
        ];
    }

    /**
     * Saves the object to the database returning the id of the object
     *
     * @param \redhotmayo\billing\Subscription $subscription
     * @return int
     */
    public function save(Subscription $subscription) {
        $id = $subscription->getId();

        return isset($id) ? $this->update($subscription) : $this->create($subscription);
    }

    /**
     * Creates a new instance of the object
     *
     * @param \redhotmayo\billing\Subscription $subscription
     * @return int
     */
    private function create(Subscription $subscription) {
        $values = $this->getValues($subscription, false);

        $id = DB::table(self::TABLE_NAME)->insertGetId($values);
        $subscription->setId($id);

        return $id;
    }

    /**
     * Updates an existing instance of the object
     *
     * @param \redhotmayo\billing\Subscription $subscription
     * @return int
     */
    private function update(Subscription $subscription) {
        $id     = $subscription->getId();
        $values = $this->getValues($subscription, true);

        DB::table(self::TABLE_NAME)
          ->where(self::C_ID, '=', $id)
          ->update($values);

        return $id;
    }

    /**
     * Gets the values used for storing this object
     *
     * @param \redhotmayo\billing\Subscription $billing
     * @param bool $updating
     * @return array
     */
    protected function getValues(Subscription $billing, $updating = false) {
        $values = [
            self::C_PLAN_ID              => $billing->getPlanId(),
            self::C_STATUS               => $billing->isActive(),
            self::C_CUSTOMER_TOKEN       => $billing->getCustomerToken(),
            self::C_SUBSCRIPTION_ENDS_AT => $billing->getSubscriptionEndDate(),
            self::C_AUTO_RENEW           => $billing->isSetToRenew(),
            self::C_TRIAL_END            => $billing->getTrialEndDate(),
            self::C_CANCELED_AT          => $billing->getCanceledDate(),
            self::C_UPGRADED_AT          => $billing->getUpgradedDate(),
            self::C_UPGRADED_ID          => $billing->getUpgradedPlanId(),
            self::C_UPDATED_AT           => Carbon::now(),
        ];

        if (!$updating) {
            $values[self::C_CREATED_AT] = Carbon::now();
        }

        return $this->encrypt($values);
    }

    /**
     * Obtain a list of encrypted columns
     *
     * @return array
     */
    public function getEncryptedColumns() {
        return [
            self::C_CUSTOMER_TOKEN
        ];
    }
}
