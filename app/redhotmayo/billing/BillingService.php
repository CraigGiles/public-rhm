<?php  namespace redhotmayo\billing;

use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\model\User;

interface BillingService {
    public function subscribe(User $user, BillingPlan $plan, $token);

//    /** @var BillingInterface $billingProvider */
//    private $billingProvider;
//
//    /** @var BillingRepository $billingRepo */
//    private $billingRepo;
//
//    public function __construct(BillingInterface $billingProvider, BillingRepository $billingRepository) {
//        $this->billingProvider = $billingProvider;
//        $this->billingRepo = $billingRepository;
//    }
//
//    public function subscribe(User $user, BillingPlan $plan, $token) {
//        //get the stripe_customer_id from the database that associates with this users id
//        $customerId = $this->billingRepo->getCustomerToken($user);
//
//        if ($this->billingRepo->getUnknownCustomerToken() === $customerId) {
//            //customer account needs to be created
//            $customerId = $this->billingProvider->createStripeCustomer($user, $token);
//        }
//
//        $this->billingProvider->subscribe($customerId, $plan);
//
//        //if stripe id doesn't exist, we'll need to create a new customer
//        $billable = new StripeBillableUser($user, $token);
//        $this->billingProvider->subscribe($billable, $plan);
//
//    }
}
