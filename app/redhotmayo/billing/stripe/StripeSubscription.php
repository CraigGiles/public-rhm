<?php  namespace redhotmayo\billing\stripe;

use Carbon\Carbon;
use redhotmayo\billing\Subscription;
use redhotmayo\exception\NullArgumentException;
use redhotmayo\model\DataObject;
use redhotmayo\utility\Arrays;

class StripeSubscription extends DataObject implements Subscription {
    const PLAN_ID                     = 'plan_id';
    const STRIPE_STATUS               = 'current_status';
    const STRIPE_CUSTOMER_TOKEN       = 'customer';
    const STRIPE_CANCEL_AT_PERIOD_END = 'cancel_at_period_end';
    const STRIPE_CURRENT_PERIOD_END   = 'subscription_ends_at';
    const STRIPE_TRIAL_END            = 'trial_end';
    const STRIPE_CANCELED_AT          = 'canceled_at';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    private $planId;
    private $status;
    private $customer;
    private $cancel_at_period_end;
    private $current_period_end;
    private $trial_end;
    private $canceled_at;
    private $upgraded_at;
    private $upgraded_id;

    public function __construct(array $data) {
        $this->parse($data);
    }

    public function update(array $data) {
        $this->parse($data);
    }

    /**
     * Get the date in which the user upgraded their account. This date
     * being present ensures that the current row in the database is old
     *
     * @return Carbon
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUpgradedDate() {
        return $this->upgraded_at;
    }

    /**
     * Get the id of the plan in which the user updated to
     *
     * @return int
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUpgradedPlanId() {
        return $this->upgraded_id;
    }

    /**
     * Is the current subscription active?
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function isActive() {
        // This will result to true in two ways:
        // - if the status is 'active'
        // - if the current_period_end is in the future
        return 'active' === $this->status ||
            (($this->current_period_end instanceof Carbon) && $this->current_period_end->isFuture());
    }

    /**
     * Is the current subscription set to renew when it runs out?
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function isSetToRenew() {
        return !$this->cancel_at_period_end;
    }

    /**
     * Is the current subscription on trial period?
     *
     * @return Carbon
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function isOnTrial() {
        return ($this->trial_end instanceof Carbon) ? $this->trial_end->isFuture() : false;
    }

    /**
     * Get the ending date for the trial period
     *
     * @return Carbon|null
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getTrialEndDate() {
        return $this->trial_end;
    }

    /**
     * Obtain the Plan ID for this subscription
     *
     * @return int
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPlanId() {
        return $this->planId;
    }

    /**
     * Obtain the customer token used to identify the charged client
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCustomerToken() {
        return $this->customer;
    }

    /**
     * Return the ending date of the current billing cycle
     *
     * @return Carbon
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getSubscriptionEndDate() {
        return $this->current_period_end;
    }

    /**
     * Get the date the subscription was canceled by the client
     *
     * @return Carbon|null
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCanceledDate() {
        return $this->canceled_at;
    }

    /**
     * Mark the subscription as upgraded
     *
     * @param Subscription $newSub
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function upgraded(Subscription $newSub) {
        $this->upgraded_at = Carbon::now();
        $this->upgraded_id = $newSub->getId();
        $this->status = 'inactive';
    }

    /**
     * Mark the subscription as canceled
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function cancel() {
        //canceled_at is set via stripe's API, do not re-set it here
        $this->status = 'inactive';
        $this->canceled_at = Carbon::now();
        $this->cancel_at_period_end = true;
    }

    private function parse(array $data) {
        $this->setId(Arrays::GetValue($data, self::ID, null));
        $this->planId               = Arrays::GetValue($data, self::PLAN_ID, null);
        $this->status               = Arrays::GetValue($data, self::STRIPE_STATUS, 'inactive');
        $this->customer             = Arrays::GetValue($data, self::STRIPE_CUSTOMER_TOKEN, '');
        $this->cancel_at_period_end = Arrays::GetValue($data, self::STRIPE_CANCEL_AT_PERIOD_END, true);
        $this->current_period_end   = Arrays::GetValue($data, self::STRIPE_CURRENT_PERIOD_END, null);
        $this->trial_end            = Arrays::GetValue($data, self::STRIPE_TRIAL_END, null);
        $this->canceled_at          = Arrays::GetValue($data, self::STRIPE_CANCELED_AT, null);

        if (!isset($this->planId) || !isset($this->current_period_end)) {
            throw new NullArgumentException('Subscription data is invalid');
        }
    }

}
