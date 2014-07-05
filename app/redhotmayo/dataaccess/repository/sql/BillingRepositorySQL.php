<?php namespace redhotmayo\dataaccess\repository\sql;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use redhotmayo\billing\NullSubscription;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\billing\stripe\StripeSubscription;
use redhotmayo\billing\Subscription;
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
        $planId = DB::table(BillingStripeSQL::TABLE_NAME)
                         ->select(BillingStripeSQL::C_PLAN_ID)
                         ->where(BillingStripeSQL::C_ID, '=', $user->getStripeBillingId())
                         ->first();

        $planId = json_decode(json_encode($planId), true);
        return BillingPlan::CreateFromId($planId[BillingStripeSQL::C_PLAN_ID]);
    }

    /**
     * Upgrade the users subscription
     *
     * @param int $oldId
     * @param Subscription $currentSub
     * @param Subscription $newSub
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function upgrade($oldId, Subscription $currentSub, Subscription $newSub) {
        DB::beginTransaction();

        $this->save($newSub);
        $currentSub->setId($oldId);
        $currentSub->upgraded($newSub);
        $this->save($currentSub);

        DB::commit();
    }

    public function getSubscriptionForUser(User $user) {
        $result = (array)DB::table(BillingStripeSQL::TABLE_NAME)
            ->where(BillingStripeSQL::C_ID, '=', $user->getStripeBillingId())
            ->first();

        $decrypted = $this->dao->decrypt($result);
        if (is_array($decrypted) && empty($decrypted)) {
            return new NullSubscription();
        }

        $cancelAtEnd = (bool)$decrypted[BillingStripeSQL::C_AUTO_RENEW] ? false : true;
        $subEndsAt = isset($decrypted[BillingStripeSQL::C_SUBSCRIPTION_ENDS_AT]) ? Carbon::createFromFormat('Y-m-d H:i:s', $decrypted[BillingStripeSQL::C_SUBSCRIPTION_ENDS_AT]) : null;
        $trialEndsAt = isset($decrypted[BillingStripeSQL::C_TRIAL_END]) ? Carbon::createFromFormat('Y-m-d H:i:s', $decrypted[BillingStripeSQL::C_TRIAL_END]) : null;
        $canceledAt = isset($decrypted[BillingStripeSQL::C_CANCELED_AT]) ? Carbon::createFromFormat('Y-m-d H:i:s', $decrypted[BillingStripeSQL::C_CANCELED_AT]) : null;

        return new StripeSubscription([
            StripeSubscription::ID                          => $decrypted[BillingStripeSQL::C_ID],
            StripeSubscription::PLAN_ID                     => $decrypted[BillingStripeSQL::C_PLAN_ID],
            StripeSubscription::STRIPE_STATUS               => $decrypted[BillingStripeSQL::C_STATUS],
            StripeSubscription::STRIPE_CUSTOMER_TOKEN       => $decrypted[BillingStripeSQL::C_CUSTOMER_TOKEN],
            StripeSubscription::STRIPE_CANCEL_AT_PERIOD_END => $cancelAtEnd,
            StripeSubscription::STRIPE_CURRENT_PERIOD_END   => $subEndsAt,
            StripeSubscription::STRIPE_TRIAL_END            => $trialEndsAt,
            StripeSubscription::STRIPE_CANCELED_AT          => $canceledAt,
        ]);
    }
}
