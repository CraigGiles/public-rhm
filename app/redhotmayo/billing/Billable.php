<?php  namespace redhotmayo\billing;

interface Billable {
    /**
     * Get the id of the current user
     *
     * @return int
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUserId();

    /**
     * Customer token given to us via our billing provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCustomerToken();

    /**
     * Credit Card token given to us via our billing provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getBillingToken();

    /**
     * Get the description used by the service provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCustomerDescription();
}
