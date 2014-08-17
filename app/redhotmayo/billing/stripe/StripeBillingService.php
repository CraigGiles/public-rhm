<?php  namespace redhotmayo\billing\stripe;

use redhotmayo\billing\BillingService;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\billing\Subscription;
use redhotmayo\dataaccess\repository\BillingRepository;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\model\Region;
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

    /**
     * Set the users billing token. If the user is a new user, this
     * MUST be triggered before any subscribe calls.
     *
     * @param $token
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function setBillingToken($token) {
        $this->billingToken = $token;
    }

    /**
     * Get the active subscription
     *
     * @param User $user
     * @return Subscription
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getSubscriptionForUser(User $user) {
        return $this->billingRepo->getSubscriptionForUser($user);
    }

    /**
     * Subscribe a user to our billing system. If the client is new than
     * a new customer record will be constructed. If the client is
     * existing, than our billing records will be updated with
     * the new subscription.
     *
     * @see setBillingToken
     *
     * @param User $user
     * @return Subscription
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function subscribe(User $user) {
        $plan          = $this->createBillingPlanForUser($user);
        $customerToken = $this->billingRepo->getCustomerToken($user);
        $stripeUser    = new StripeBillableUser($user, $this->billingToken, $customerToken);
        $current       = $this->getActiveSubscription($stripeUser);

        isset($current) ?
            $this->updateExistingSubscription($stripeUser, $plan, $current) :
            $this->createNewSubscription($stripeUser, $plan);
    }

    /**
     * Cancel a users billing subscription. The user will continue to enjoy
     * the product until the current billing subscription expires.
     *
     * @param User $user
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function cancel(User $user) {
        $customerToken = $this->billingRepo->getCustomerToken($user);
        $stripeUser    = new StripeBillableUser($user, $this->billingToken, $customerToken);
        $current       = $this->getActiveSubscription($stripeUser);
        $result        = $this->gateway->cancel($stripeUser);

        if ($result) {
            $current->setId($user->getStripeBillingId());
            $current->cancel();
            $this->billingRepo->save($current);
        }

        return $result;
    }

    /**
     * Creates a new billing plan based on the users subscribed population
     *
     * @param User $user
     * @return BillingPlan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function createBillingPlanForUser(User $user) {
        $population = $this->getPopulationCountForUser($user);
        return BillingPlan::CreateFromPopulation($population);
    }

    /**
     * Obtains the current population count for the users subscribed territories
     *
     * @param User $user
     * @return int
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPopulationCountForUser(User $user) {
        $userZips   = $this->subRepo->getAllZipcodesForUser($user);
        return $this->zipRepo->getPopulationForZipcodes($userZips);
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
        StripeBillableUser $user, BillingPlan $plan, StripeSubscription $current
    ) {
        $subscription = $this->gateway->updateExistingSubscription($user, $plan);
        $rhmUser      = $user->getUserObject();

        $this->billingRepo->upgrade($rhmUser->getStripeBillingId(), $current, $subscription);

        $rhmUser->setStripeBillingId($subscription->getId());
        $this->userRepo->save($rhmUser);
    }

    /**
     * Given an array of regions, output the proposed total for each billing cycle
     *
     * @param array $regions
     * @return int
     */
    public function getProposedTotalForRegions(array $regions) {
        $zipcodes = [];

        foreach ($regions as $region) {
            if (!$region instanceof Region) {
                throw new \InvalidArgumentException("Region must be instanceof Region class");
            }

            $zips = $this->zipRepo->getZipcodesFromCity($region->getCity(), $region->getState());
            $zipcodes = array_merge($zipcodes, $zips);
        }

        if (empty($zipcodes)) {
            return 0;
        }

        $population = $this->zipRepo->getPopulationForZipcodes($zipcodes);
        $plan = BillingPlan::CreateFromPopulation($population);

        return $plan->getPrice();
    }
}

