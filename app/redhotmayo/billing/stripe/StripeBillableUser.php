<?php  namespace redhotmayo\billing\stripe;

use redhotmayo\billing\Billable;
use redhotmayo\model\User;

class StripeBillableUser implements Billable {
    private $user;
    private $billingToken;
    private $customerToken;

    public function __construct(User $user, $billingToken) {
        $this->user = $user;
        $this->billingToken = $billingToken;
        $this->customerToken = null;
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
}
