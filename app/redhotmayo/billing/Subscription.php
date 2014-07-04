<?php  namespace redhotmayo\billing;

use Carbon\Carbon;

interface Subscription {
    public function getId();
    public function setId($id);

    /**
     * Obtain the Plan ID for this subscription
     *
     * @return int
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPlanId();

    /**
     * Obtain the customer token used to identify the charged client
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCustomerToken();

    /**
     * Return the ending date of the current billing cycle
     *
     * @return Carbon
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getSubscriptionEndDate();

    /**
     * Is the current subscription active?
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function isActive();

    /**
     * Is the current subscription set to renew when it runs out?
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function isSetToRenew();

    /**
     * Is the current subscription on trial period?
     *
     * @return Carbon
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function isOnTrial();

    /**
     * Get the ending date for the trial period
     *
     * @return Carbon
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getTrialEndDate();

    /**
     * Get the date the subscription was canceled by the client
     *
     * @return Carbon
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCanceledDate();

    /**
     * Get the date in which the user upgraded their account. This date
     * being present ensures that the current row in the database is old
     *
     * @return Carbon
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUpgradedDate();

    /**
     * Get the id of the plan in which the user updated to
     *
     * @return int
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUpgradedPlanId();


    /**
     * Mark the subscription as upgraded
     *
     * @param Subscription $newSub
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function upgraded(Subscription $newSub);

    /**
     * Mark the subscription as canceled
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function cancel();
}
