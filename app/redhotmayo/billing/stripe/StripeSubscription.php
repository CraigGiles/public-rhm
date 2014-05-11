<?php  namespace redhotmayo\billing\stripe;

use Carbon\Carbon;
use redhotmayo\billing\Subscription;
use redhotmayo\utility\Arrays;

class StripeSubscription implements Subscription {
    const STRIPE_START                   = 'start';
    const STRIPE_STATUS                  = 'status';
    const STRIPE_CUSTOMER                = 'customer';
    const STRIPE_CANCEL_AT_PERIOD_END    = 'cancel_at_period_end';
    const STRIPE_CURRENT_PERIOD_START    = 'current_period_start';
    const STRIPE_CURRENT_PERIOD_END      = 'current_period_end';
    const STRIPE_ENDED_AT                = 'ended_at';
    const STRIPE_TRIAL_START             = 'trial_start';
    const STRIPE_TRIAL_END               = 'trial_end';
    const STRIPE_CANCELED_AT             = 'canceled_at';
    const STRIPE_QUANTITY                = 'quantity';
    const STRIPE_APPLICATION_FEE_PERCENT = 'application_fee_percent';
    const STRIPE_DISCOUNT                = 'discount';

    private $start;
    private $status;
    private $customer;
    private $cancel_at_period_end;
    private $current_period_start;
    private $current_period_end;
    private $ended_at;
    private $trial_start;
    private $trial_end;
    private $canceled_at;
    private $quantity;
    private $application_fee_percent;
    private $discount;

    public function __construct(array $data) {
        $ts = $this->defaultTimeStamp();

        $this->start                   = Arrays::GetValue($data, self::STRIPE_START, $ts);
        $this->status                  = Arrays::GetValue($data, self::STRIPE_STATUS, 'inactive');
        $this->customer                = Arrays::GetValue($data, self::STRIPE_CUSTOMER, '');
        $this->cancel_at_period_end    = Arrays::GetValue($data, self::STRIPE_CANCEL_AT_PERIOD_END, $ts);
        $this->current_period_start    = Arrays::GetValue($data, self::STRIPE_CURRENT_PERIOD_START, $ts);
        $this->current_period_end      = Arrays::GetValue($data, self::STRIPE_CURRENT_PERIOD_END, $ts);
        $this->ended_at                = Arrays::GetValue($data, self::STRIPE_ENDED_AT, $ts);
        $this->trial_start             = Arrays::GetValue($data, self::STRIPE_TRIAL_START, $ts);
        $this->trial_end               = Arrays::GetValue($data, self::STRIPE_TRIAL_END, $ts);
        $this->canceled_at             = Arrays::GetValue($data, self::STRIPE_CANCELED_AT, $ts);
        $this->quantity                = Arrays::GetValue($data, self::STRIPE_QUANTITY, 0);
        $this->application_fee_percent = Arrays::GetValue($data, self::STRIPE_APPLICATION_FEE_PERCENT, 0);
        $this->discount                = Arrays::GetValue($data, self::STRIPE_DISCOUNT, 0);
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
        return $this->getTrialEndDate()
                    ->isFuture();
    }

    /**
     * Get the ending date for the trial period
     *
     * @return Carbon
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getTrialEndDate() {
        return $this->trial_end;
    }

    private function defaultTimeStamp() {
        return Carbon::create(1900, 1, 1);
    }
}
