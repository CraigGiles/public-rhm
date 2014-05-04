<?php  namespace redhotmayo\billing;

use redhotmayo\billing\plan\BillingPlan;

interface BillingInterface {
    /**
     * Subscribe a user to a billing plan
     *
     * @param BillingPlan $plan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function subcribe(BillingPlan $plan);

    /**
     * Change the users current billing plan for a new billing plan
     *
     * @param BillingPlan $plan
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function change(BillingPlan $plan);

    /**
     * Cancel the current billing plan
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function cancel();

    /**
     * Return the users current billing plan
     *
     * @return BillingPlan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getBillingPlan();

    /**
     * Return true if the user has an active subscription, false otherwise
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function isSubscribed();
} 
