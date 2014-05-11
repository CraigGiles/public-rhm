<?php  namespace redhotmayo\billing;

use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\billing\stripe\StripeBillableUser;
use redhotmayo\dataaccess\repository\BillingRepository;
use redhotmayo\model\User;

class BillingService {
    /** @var BillingInterface $billingProvider */
    private $billingProvider;

    /** @var BillingRepository $billingRepo */
    private $billingRepo;

    public function __construct(BillingInterface $billingProvider, BillingRepository $billingRepository) {
        $this->billingProvider = $billingProvider;
        $this->billingRepo = $billingRepository;
    }

    public function subscribe(User $user, BillingPlan $plan, $token) {
        //get the stripe_customer_id from the database that associates with this users id
        $customerId = $this->billingRepo->getCustomerToken($user);

        if ($customerId === false) {
            //customer account needs to be created
            $customerId = $this->billingProvider->createCustomer($user, $token);
        }

        $this->billingProvider->subscribe($customerId, $plan);


        //if stripe id doesn't exist, we'll need to create a new customer
        $billable = new StripeBillableUser($user, $token);
        $this->billingProvider->subscribe($billable, $plan);

    }
}
