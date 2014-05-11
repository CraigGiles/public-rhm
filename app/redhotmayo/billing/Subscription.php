<?php  namespace redhotmayo\billing;

use Carbon\Carbon;

interface Subscription {
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
}
