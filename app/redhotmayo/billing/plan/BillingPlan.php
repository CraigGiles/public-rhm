<?php  namespace redhotmayo\billing\plan;

interface BillingPlan {
    /**
     * The unique id that identifies this plan with our billing provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getId();

    /**
     * Human readable name associated with this plan
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getName();

    /**
     * How much will the customer be charged
     *
     * @return double
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPrice();

    /**
     * How often the customer will be charged
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPeriod();

    /**
     * Number of days this plan has for a trial period
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getTrialPeriod();
}
