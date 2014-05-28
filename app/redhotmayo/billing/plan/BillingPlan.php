<?php  namespace redhotmayo\billing\plan;

use Illuminate\Support\Facades\Config;
use redhotmayo\exception\NullArgumentException;
use redhotmayo\utility\Arrays;

class BillingPlan {
    /** Config path to grab plan data */
    const ALL_PLANS = 'billing.plans';

    /** Index information for plans within config data */
    const UNSUBSCRIBED = 0;
    const BASIC        = 1;
    const PREMIUM      = 2;

    /** array fields for plans within config data */
    const PLAN_ID                = 'id';
    const PLAN_NAME              = 'name';
    const PLAN_PRICE             = 'price';
    const PLAN_SUBSCRIPTION_TERM = 'subscription_term';
    const PLAN_TRIAL_PERIOD      = 'trial_period';

    private $id;
    private $name;
    private $price;
    private $subscriptionTerm;
    private $trialPeriod;

    /**
     * Load the given $planId's plan and return a new object with the plan details.
     *
     * @param int $planId
     * @return BillingPlan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public static function CreateFromId($planId) {
        $plans = Config::get(self::ALL_PLANS);
        $plan  = Arrays::GetValue($plans, (int)$planId, null);

        return self::CreateWithData($plan);
    }

    /**
     * Create a new object with the given plan details
     *
     * @param array $plan
     * @return BillingPlan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public static function CreateWithData(array $plan) {
        $id    = Arrays::GetValue($plan, self::PLAN_ID, null);
        $name  = Arrays::GetValue($plan, self::PLAN_NAME, null);
        $price = Arrays::GetValue($plan, self::PLAN_PRICE, null);
        $term  = Arrays::GetValue($plan, self::PLAN_SUBSCRIPTION_TERM, null);
        $trial = Arrays::GetValue($plan, self::PLAN_TRIAL_PERIOD, null);

        return new self($id, $name, $price, $term, $trial);
    }

    public function __construct($id, $name, $price, $term, $trial) {
        $this->filterNull($id);
        $this->filterNull($name);
        $this->filterNull($price);
        $this->filterNull($term);
        $this->filterNull($trial);

        $this->id               = $id;
        $this->name             = $name;
        $this->price            = $price;
        $this->subscriptionTerm = $term;
        $this->trialPeriod      = $trial;
    }

    /**
     * The unique id that identifies this plan with our billing provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Human readable name associated with this plan
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getName() {
        return $this->name;
    }

    /**
     * How much will the customer be charged
     *
     * @return double
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * How often the customer will be charged
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getSubscriptionTerm() {
        return $this->subscriptionTerm;
    }

    /**
     * Number of days this plan has for a trial period
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getTrialPeriod() {
        return $this->trialPeriod;
    }

    /**
     * Filter out any values that are null
     *
     * @param $value
     *
     * @throws \redhotmayo\exception\NullArgumentException
     * @author Craig Giles < craig@gilesc.com >
     *
     * TODO: create some sort of utility object / type checker?
     */
    private function filterNull($value) {
        if (!isset($value)) {
            throw new NullArgumentException('No Billing Plan value can be null');
        }
    }
}
