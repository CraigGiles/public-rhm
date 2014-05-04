<?php  namespace redhotmayo\billing\plan;

use Illuminate\Support\Facades\Config;

abstract class RedHotMayoBillingPlan implements BillingPlan {
    const ID = 'UNSET';
    const NAME = 'UNSET';
    const PRICE = 'UNSET';
    const SUBSCRIPTION_TERM = 'UNSET';
    const TRIAL_PERIOD = 'UNSET';

    /**
     * The unique id that identifies this plan with our billing provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getId() {
        return Config::get(static::ID);
    }

    /**
     * Human readable name associated with this plan
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getName() {
        return Config::get(static::NAME);
    }

    /**
     * How much will the customer be charged
     *
     * @return double
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPrice() {
        return Config::get(static::PRICE);
    }

    /**
     * How often the customer will be charged
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getSubscriptionTerm() {
        return Config::get(static::SUBSCRIPTION_TERM);
    }

    /**
     * Number of days this plan has for a trial period
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getTrialPeriod() {
        return Config::get(static::TRIAL_PERIOD);
    }
}
