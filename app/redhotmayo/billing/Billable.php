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
     * Customer ID used to identify the client on our billing provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCustomerToken();

    /**
     * Email address of the client
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getEmail();
}
