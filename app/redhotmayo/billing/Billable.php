<?php  namespace redhotmayo\billing;

interface Billable {
    /**
     * Customer ID used to identify the client on our billing provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCustomerId();

    /**
     * Last four digits of the clients credit card number
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getLastFour();

    /**
     * Email address of the client
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getEmail();
}
