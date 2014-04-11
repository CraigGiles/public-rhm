<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\EncryptedTrait;
use redhotmayo\dataaccess\repository\dao\BillingDAO;
use redhotmayo\model\Billing;

class BillingSQL implements BillingDAO {
    use EncryptedTrait;

    const TABLE_NAME = 'billing';

    const C_ID = 'id';
    const C_STRIPE_ACTIVE = 'stripe_active';
    const C_STRIPE_ID = 'stripe_id';
    const C_STRIPE_PLAN = 'stripe_plan';
    const C_LAST_FOUR = 'last_four';
    const C_TRIAL_ENDS_AT = 'trial_ends_at';
    const C_SUBSCRIPTION_ENDS_AT = 'subscription_ends_at';

    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    /**
     * Save a record and return the objectId
     *
     * @param \redhotmayo\model\Billing $billing
     * @return int
     */
    public function save(Billing $billing) {
        $billingId = $billing->getBillingId();

        if (isset($billingId)) {
            $this->update($billing);
            return $billingId;
        } else {
            $values = $this->getValues($billing, false);

            $id = DB::table(self::TABLE_NAME)
                    ->insertGetId($values);

            $billing->setUserId($id);
            return $id;
        }
    }

    public function update(Billing $billing) {
        $id = $billing->getBillingId();
        $values = $this->getValues($billing, isset($id));
        DB::table('billing')
          ->where(self::C_ID, $id)
          ->update($values);
    }

    private function getValues(Billing $billing, $updating=false) {
        $values = [
            self::C_STRIPE_ID => Crypt::encrypt($billing->getStripeId()),
            self::C_LAST_FOUR => Crypt::encrypt($billing->getLastFourCardDigits()),
            self::C_STRIPE_PLAN => $billing->getStripePlan(),
            self::C_STRIPE_ACTIVE => $billing->subscribed(),
            self::C_SUBSCRIPTION_ENDS_AT => $billing->getSubscriptionEndDate(),
            self::C_UPDATED_AT => Carbon::now(),
        ];

        if (!$updating) {
            $values[self::C_CREATED_AT] = Carbon::now();
        }

        return $this->encrypt($values);
    }

    /**
     * Obtain a list of encrypted columns
     */
    public function getEncryptedColumns() {
        return [
            self::C_STRIPE_ID,
            self::C_LAST_FOUR
        ];
    }
}
