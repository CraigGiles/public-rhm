<?php  namespace redhotmayo\billing\stripe;

use redhotmayo\billing\BillingService;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\dataaccess\repository\BillingRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\model\User;

class StripeBillingService implements BillingService {
    const PRIVATE_API_KEY        = 'stripe.private_key';
    const UNKNOWN_CUSTOMER_TOKEN = '';

    /** @var \redhotmayo\billing\stripe\StripeGateway $gateway */
    private $gateway;

    /** @var \redhotmayo\dataaccess\repository\BillingRepository $billingRepo */
    private $billingRepo;

    /** @var \redhotmayo\dataaccess\repository\UserRepository $userRepo */
    private $userRepo;

    public function __construct(
        BillingRepository $billingRepository, UserRepository $userRepo, StripeGateway $gateway
    ) {
        $this->userRepo    = $userRepo;
        $this->gateway     = $gateway;
        $this->billingRepo = $billingRepository;
    }

    public function subscribe(User $user, BillingPlan $plan, $token) {
        $stripeUser = new StripeBillableUser($user, $token);
        $current = $this->getActiveSubscription($stripeUser);

        isset($current) ? $this->updateExistingSubscription($stripeUser, $plan, $current) :
            $this->createNewSubscription($stripeUser, $plan);
    }

    private function getActiveSubscription(StripeBillableUser $user) {
        $active        = null;
        $subCollection = $this->gateway->getActiveSubscriptions($user);
        $iter          = $subCollection->getIterator();

        // continue until you find an active subscription, then return that subscription
        while ($iter->valid()) {
            /** @var StripeSubscription $sub */
            $sub = $iter->current();

            if (!$sub->isActive()) {
                $iter->next();
                continue;
            }

            // TODO: this will currently only give you the first active subscription. If the user has multiple active subscriptions, this wont give them all back
            $active = $sub;
            break;
        }

        return $active;
    }

    private function createNewSubscription(StripeBillableUser $user, BillingPlan $plan) {
        $subscription = $this->gateway->createNewSubscription($user, $plan);
        $rhmUser = $user->getUserObject();
        $this->billingRepo->save($subscription);

        $rhmUser->setBillingId($subscription->getId());
        $this->userRepo->save($rhmUser);
    }
}

