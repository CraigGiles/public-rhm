<?php  namespace redhotmayo\billing\plan; 
use Illuminate\Support\Facades\Config;

class PremiumBillingPlan implements BillingPlan {
    /**
     * The unique id that identifies this plan with our billing provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getId() {
        return Config::get('stripe.plans.premium.id');
    }

    /**
     * Human readable name associated with this plan
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getName() {
        return Config::get('stripe.plans.premium.name');
    }

    /**
     * How much will the customer be charged
     *
     * @return double
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPrice() {
        return Config::get('stripe.plans.premium.price');
    }

    /**
     * How often the customer will be charged
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPeriod() {
        return Config::get('stripe.plans.premium.period');
    }

    /**
     * Number of days this plan has for a trial period
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getTrialPeriod() {
        return Config::get('stripe.plans.premium.trial_period');
    }
}
