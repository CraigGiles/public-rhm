<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\encryption\EncryptedSQLTable;
use redhotmayo\dataaccess\repository\dao\BillingStripeDAO;
use redhotmayo\model\Billing;

class BillingStripeSQL extends EncryptedSQLTable implements BillingStripeDAO {
    const TABLE_NAME = 'billing';

    const C_ID = 'id';

    const C_USER_ID = 'user_id';
    const C_PLAN_ID = 'plan_id';
    const C_CUSTOMER_TOKEN = 'customer_token';
    const C_SUBSCRIPTION_ENDS_AT = 'subscription_ends_at';

    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    /**
     * Saves the object to the database returning the id of the object
     *
     * @param \redhotmayo\model\Billing $billing
     * @return int
     */
    public function save(Billing $billing) {
        $id = $billing->getId();
        return isset($id) ? $this->update($billing) : $this->create($billing);
    }

    /**
     * Creates a new instance of the object
     *
     * @param \redhotmayo\model\Billing $billing
     * @return int
     */
    private function create(Billing $billing) {
        $values = $this->getValues($billing, false);

        $id = DB::table(self::TABLE_NAME)->insertGetId($values);
        $billing->setId($id);

        return $id;
    }

    /**
     * Updates an existing instance of the object
     *
     * @param \redhotmayo\model\Billing $billing
     * @return int
     */
    private function update(Billing $billing) {
        $id = $billing->getId();
        $values = $this->getValues($billing, true);

        DB::table(self::TABLE_NAME)
          ->where(self::C_ID, '=', $id)
          ->update($values);

        return $id;
    }

    /**
     * Gets the values used for storing this object
     *
     * @param \redhotmayo\model\Billing $billing
     * @param bool
     * @return array
     */
    protected function getValues(Billing $billing, $updating = false) {
        $values = [
            self::C_USER_ID => $billing->getUserId(),
            self::C_PLAN_ID => $billing->getPlanId(),
            self::C_CUSTOMER_TOKEN => $billing->getCustomerToken(),
            self::C_SUBSCRIPTION_ENDS_AT => $billing->getSubscriptionEndsAt(),
            self::C_UPDATED_AT => Carbon::now(),
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
