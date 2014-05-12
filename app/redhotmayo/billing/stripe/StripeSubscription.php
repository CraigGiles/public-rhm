<?php  namespace redhotmayo\billing\stripe;

use Carbon\Carbon;
use redhotmayo\billing\Subscription;
use redhotmayo\exception\NullArgumentException;
use redhotmayo\model\DataObject;
use redhotmayo\utility\Arrays;

class StripeSubscription extends DataObject implements Subscription {
    const PLAN_ID                     = 'plan_id';
    const STRIPE_STATUS               = 'status';
    const STRIPE_CUSTOMER_TOKEN       = 'customer';
    const STRIPE_CANCEL_AT_PERIOD_END = 'cancel_at_period_end';
    const STRIPE_CURRENT_PERIOD_END   = 'current_period_end';
    const STRIPE_TRIAL_END            = 'trial_end';
    const STRIPE_CANCELED_AT          = 'canceled_at';

    private $planId;
    private $status;
    private $customer;
    private $cancel_at_period_end;
    private $current_period_end;
    private $trial_end;
    private $canceled_at;

    public function __construct(array $data) {
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

    /**
     * Is the current subscription active?
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function isActive() {
        return 'active' === $this->status;
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
}
