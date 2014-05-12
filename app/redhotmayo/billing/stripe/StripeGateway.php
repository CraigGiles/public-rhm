<?php  namespace redhotmayo\billing\stripe;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use redhotmayo\billing\exception\BillingException;
use redhotmayo\billing\exception\CardErrorException;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\model\Billing;
use redhotmayo\utility\Arrays;

class StripeGateway {
    public function getApiKey() {
        return Config::get('stripe.private_key');
    }

    public function createNewSubscription(StripeBillableUser $user, BillingPlan $plan) {
        try {
            $customer = $this->getStripeCustomer($user);

            /** @var \Stripe_Subscription $result */
            $result = $customer->subscriptions->create([
                "plan" => $plan->getId()
            ]);

            $trialEnd = isset($result->trial_end) ? Carbon::createFromTimestampUTC($result->trial_end) : null;
            $canceledAt = isset($result->canceled_at) ? Carbon::createFromTimestampUTC($result->canceled_at) : null;

            $billing = new StripeSubscription([
                StripeSubscription::PLAN_ID => $plan->getId(),
                StripeSubscription::STRIPE_STATUS => $result->status,
                StripeSubscription::STRIPE_CUSTOMER_TOKEN => $result->customer,
                StripeSubscription::STRIPE_CANCEL_AT_PERIOD_END => $result->cancel_at_period_end,
                StripeSubscription::STRIPE_CURRENT_PERIOD_END => Carbon::createFromTimestampUTC($result->current_period_end),
                StripeSubscription::STRIPE_TRIAL_END => $trialEnd,
                StripeSubscription::STRIPE_CANCELED_AT => $canceledAt,
            ]);

            return $billing;
        } catch (\Stripe_CardError $cardError) {
            $body    = $cardError->getJsonBody();
            $message = Arrays::GetValue($body, 'message', '');
            Log::error($message);
            throw new CardErrorException($message);
        } catch (\Stripe_ApiConnectionError $apiConnectionError) {
            $body    = $apiConnectionError->getJsonBody();
            $message = Arrays::GetValue($body, 'message', '');
            Log::error($message);
            throw new BillingException('Network connection with billing partner failed.');
        } catch (\Stripe_Error $stripeError) {
            $body    = $stripeError->getJsonBody();
            $message = Arrays::GetValue($body, 'message', '');
            Log::error($message);
            throw new BillingException('Error will billing. Please try again later.');
        } catch (\Exception $ex) {
            Log::error($ex);
            throw new BillingException($ex);
        }
    }

    public function updateExistingSubscription(
        StripeBillableUser $user, BillingPlan $newPlan, StripeSubscription $subscription
    ) {
        throw new \Exception("Function currently not implemented");
    }

    public function getActiveSubscriptions(StripeBillableUser $user) {
        $subs = new Collection();

        $customer      = $this->getStripeCustomer($user);
        $subscriptions = $customer->subscriptions->all();

        foreach ($subscriptions['data'] as $subscription) {
            $subs->push(
                 new StripeSubscription(
                     [
                         StripeSubscription::STRIPE_STATUS                  => $subscription->status,
                         StripeSubscription::STRIPE_CUSTOMER_TOKEN                => $subscription->customer,
                         StripeSubscription::STRIPE_CANCEL_AT_PERIOD_END    => $subscription->cancel_at_period_end,
                         StripeSubscription::STRIPE_CURRENT_PERIOD_END      => $subscription->current_period_end,
                         StripeSubscription::STRIPE_TRIAL_END               => $subscription->trial_end,
                         StripeSubscription::STRIPE_CANCELED_AT             => $subscription->canceled_at,
                     ]
                 )
            );
        }

        return $subs;
    }

    private function createStripeCustomer(StripeBillableUser $user) {
        $customer = StripeCustomer::create([
            'card'        => $user->getBillingToken(),
            'description' => $user->getCustomerDescription(),
        ], $this->getApiKey());

        $user->setCustomerToken($customer->id);

        return $customer;
    }

    private function getStripeCustomer(StripeBillableUser $user) {
        $customerToken = $user->getCustomerToken();

        return isset($customerToken) ? StripeCustomer::retrieve($customerToken, $this->getApiKey()) :
            $this->createStripeCustomer($user);
    }

}
