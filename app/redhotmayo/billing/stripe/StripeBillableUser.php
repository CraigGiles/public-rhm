<?php  namespace redhotmayo\billing\stripe;

use redhotmayo\billing\Billable;
use redhotmayo\billing\exception\BillingException;
use redhotmayo\model\User;

class StripeBillableUser implements Billable {
    private $user;
    private $billingToken;
    private $customerToken;

    public function __construct(User $user, $billingToken=null, $customerToken=null) {
        if (!isset($billingToken) && !isset($customerToken)) {
            throw new BillingException("Either BillingToken or CustomerToken must be set");
        }

        $this->user = $user;
        $this->billingToken = $billingToken;
        $this->customerToken = $customerToken;
    }

    /**
     * Get the id of the current user
     *
     * @return int
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUserId() {
        return $this->user->getId();
    }

    /**
     * Customer token given to us via our billing provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCustomerToken() {
        return $this->customerToken;
    }

    /**
     * Sets the customer token
     *
     * @param string $id
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function setCustomerToken($id) {
        $this->customerToken = $id;
    }

    /**
     * Credit Card token given to us via our billing provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getBillingToken() {
        return $this->billingToken;
    }

    /**
     * Email address of the client
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getEmail() {
        return $this->user->getEmail();
    }


    /**
     * Get the description used by the service provider
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCustomerDescription() {
        return $this->user->getUsername();
    }

    /**
     * Get the user object
     *
     * @return User
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUserObject() {
        return $this->user;
    }
}
