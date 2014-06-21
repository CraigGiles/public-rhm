<?php  namespace redhotmayo\billing\stripe;

use redhotmayo\billing\BillingService;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\dataaccess\repository\BillingRepository;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\model\User;

class StripeBillingService implements BillingService {
    const PRIVATE_API_KEY        = 'stripe.private_key';
    const UNKNOWN_CUSTOMER_TOKEN = '';

    private $billingToken;

    /** @var \redhotmayo\billing\stripe\StripeGateway $gateway */
    private $gateway;

    /** @var \redhotmayo\dataaccess\repository\BillingRepository $billingRepo */
    private $billingRepo;

    /** @var \redhotmayo\dataaccess\repository\SubscriptionRepository $subRepo */
    private $subRepo;

    /** @var \redhotmayo\dataaccess\repository\ZipcodeRepository $zipRepo */
    private $zipRepo;

    /** @var \redhotmayo\dataaccess\repository\UserRepository $userRepo */
    private $userRepo;

    public function __construct(
        BillingRepository $billingRepository,
        UserRepository $userRepo,
        StripeGateway $gateway,
        SubscriptionRepository $subRepo,
        ZipcodeRepository $zipRepo
    ) {
        $this->userRepo    = $userRepo;
        $this->gateway     = $gateway;
        $this->billingRepo = $billingRepository;
        $this->subRepo     = $subRepo;
        $this->zipRepo     = $zipRepo;
    }

    public function setBillingToken($token) {
        $this->billingToken = $token;
    }

    /**
     * @param User $user
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUsersSubscription(User $user) {
        $tmp = $this->billingRepo->find(['id' => $user->getStripeBillingId()]);

        //get the users current plan_id from the table and return the subscription information
        //if the result doesn't exist, attempt to get the information from stripe
        //if all else fails, return the Unsubscribed subscription
    }

    public function subscribe(User $user) {
        $plan       = $this->createBillingPlan($user);
        $customerToken = $this->billingRepo->getCustomerToken($user);
        $stripeUser = new StripeBillableUser($user, $this->billingToken, $customerToken);
        $current    = $this->getActiveSubscription($stripeUser);

        isset($current) ?
            $this->updateExistingSubscription($stripeUser, $plan, $current) :
            $this->createNewSubscription($stripeUser, $plan);
    }

    /** 
     * Swaps out the current billing plan for a new billing plan based on 
     * the users subscribed population
     *
     * @param User $user
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function swap(User $user) {
//        $plan = $this->createBillingPlan($user);
//        $customerToken = $this->billingRepo->getCustomerToken($user);
//        $stripeUser = $
//
//        $billable = new Billing($user);
//        $billable->subscription($plan->getStripeId())
//            ->swap();
    }

    /**
     * Creates a new billing plan based on the users subscribed population
     *
     * @param User $user
     * @return BillingPlan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function createBillingPlan(User $user) {
        $userZips   = $this->subRepo->getAllZipcodesForUser($user);
        $population = $this->zipRepo->getPopulationForZipcodes($userZips);
        return BillingPlan::CreateFromPopulation($population);
    }

    /**
     * @param StripeBillableUser $user
     * @return StripeSubscription|null
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function getActiveSubscription(StripeBillableUser $user) {
        $subCollection = $this->gateway->getActiveSubscriptions($user);
        $active        = null;

        // continue until you find an active subscription, then return that subscription
        /** @var StripeSubscription $sub */
        foreach ($subCollection as $sub) {
            if ($sub->isActive()) {
                $active = $sub;
                break;
            }
        }

        return $active;
    }

    private function createNewSubscription(StripeBillableUser $user, BillingPlan $plan) {
        $subscription = $this->gateway->createNewSubscription($user, $plan);
        $rhmUser      = $user->getUserObject();
        $this->billingRepo->save($subscription);

        $rhmUser->setStripeBillingId($subscription->getId());
        $this->userRepo->save($rhmUser);
    }

    private function updateExistingSubscription(
        StripeBillableUser $user, BillingPlan $plan, StripeSubscription $current)
    {
        //todo
    }
}

