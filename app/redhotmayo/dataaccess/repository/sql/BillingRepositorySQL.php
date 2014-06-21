<?php namespace redhotmayo\dataaccess\repository\sql;

use Illuminate\Support\Facades\DB;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\billing\stripe\StripeSubscription;
use redhotmayo\dataaccess\repository\BillingRepository;
use redhotmayo\dataaccess\repository\dao\BillingStripeDAO;
use redhotmayo\dataaccess\repository\dao\sql\BillingStripeSQL;
use redhotmayo\model\User;
use redhotmayo\utility\Arrays;

class BillingRepositorySQL extends RepositorySQL implements BillingRepository {
    const SERVICE = 'redhotmayo\dataaccess\repository\sql\BillingRepositorySQL';

    /** @var BillingStripeDAO $dao */
    private $dao;

    public function __construct() {
        $this->dao = new BillingStripeSQL();
    }

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $object
     * @return bool
     */
    public function save($object) {
        $this->dao->save($object);
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll(array $objects) {
        // TODO: Implement saveAll() method.
    }

    public function getTableName() {
        return BillingStripeSQL::TABLE_NAME;
    }

    public function getColumns() {
        return BillingStripeSQL::GetColumns();
    }

    protected function getConstraints($parameters) {
        $constraints = [];
        $constraints[BillingStripeSQL::C_ID] = Arrays::GetValue($parameters, BillingStripeSQL::C_ID, null);

        return Arrays::RemoveNullValues($constraints);
    }

    public function getCustomerToken(User $user) {
        $token = (array)DB::table($this->getTableName())
            ->select(BillingStripeSQL::C_CUSTOMER_TOKEN)
            ->where(BillingStripeSQL::C_ID, '=', $user->getStripeBillingId())
            ->first();

        $decryptedRow = $this->dao->decrypt($token);
        return Arrays::GetValue($decryptedRow, BillingStripeSQL::C_CUSTOMER_TOKEN, $this->getUnknownCustomerToken());
    }

    /**
     * Return the value associated with an unknown customer token
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUnknownCustomerToken() {
        return BillingStripeSQL::UNKNOWN_CUSTOMER_TOKEN;
    }

    /**
     * Prepares all values returned from the database to a format which can be
     * consumed by the application. Encrypted values will be unencrypted
     * prior to conversion.
     *
     * @param $values
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    protected function filter(array $values) {
        $subscriptions = [];

        foreach ($values as $value) {
            $decrypted = $this->dao->decrypt($value);
            $subscriptions[] = new StripeSubscription($decrypted);
        }

        return $subscriptions;
    }

    /**
     * Gets the users current billing plan
     *
     * @param User $user
     * @return BillingPlan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPlanForUser(User $user) {
        $planId = (int)DB::table(BillingStripeSQL::TABLE_NAME)
                         ->select(BillingStripeSQL::C_PLAN_ID)
                         ->where(BillingStripeSQL::C_ID, '=', $user->getStripeBillingId())
                         ->first();

        return BillingPlan::CreateFromId($planId);
    }
}
