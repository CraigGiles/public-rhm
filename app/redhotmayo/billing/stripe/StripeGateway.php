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

            $billing = Billing::createWithData([
                Billing::USER_ID              => $user->getUserId(),
                Billing::CUSTOMER_TOKEN       => $user->getBillingToken(),
                Billing::PLAN_ID              => $plan->getId(),
                Billing::SUBSCRIPTION_ENDS_AT => Carbon::createFromTimestamp($result->current_period_end)
                                                       ->toDateTimeString(),
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

    public function updateExistingSubscription(StripeBillableUser $user, BillingPlan $plan) {
        throw new \Exception("Function currently not implemented");
    }

    public function getActiveSubscriptions(StripeBillableUser $user) {
        $customer      = $this->getStripeCustomer($user);
        $subscriptions = $customer->subscriptions->all();

        //foreach subscriptions as subscription
        //create BillingPlan for each subscription
        //return collection of billing plans

        $subs = new Collection();
        foreach ($subscriptions['data'] as $subscription) {
            $subs->push(
                 new StripeSubscription(
                     [
                         StripeSubscription::STRIPE_START                   => $subscription->start,
                         StripeSubscription::STRIPE_STATUS                  => $subscription->status,
                         StripeSubscription::STRIPE_CUSTOMER                => $subscription->customer,
                         StripeSubscription::STRIPE_CANCEL_AT_PERIOD_END    => $subscription->cancel_at_period_end,
                         StripeSubscription::STRIPE_CURRENT_PERIOD_START    => $subscription->current_period_start,
                         StripeSubscription::STRIPE_CURRENT_PERIOD_END      => $subscription->current_period_end,
                         StripeSubscription::STRIPE_ENDED_AT                => $subscription->ended_at,
                         StripeSubscription::STRIPE_TRIAL_START             => $subscription->trial_start,
                         StripeSubscription::STRIPE_TRIAL_END               => $subscription->trial_end,
                         StripeSubscription::STRIPE_CANCELED_AT             => $subscription->canceled_at,
                         StripeSubscription::STRIPE_QUANTITY                => $subscription->quantity,
                         StripeSubscription::STRIPE_APPLICATION_FEE_PERCENT => $subscription->application_fee_percent,
                         StripeSubscription::STRIPE_DISCOUNT                => $subscription->discount,
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

        return isset($customerToken) ? StripeCustomer::retrieve($customerToken, $this->getApiKey()) : $this->createStripeCustomer($user);
    }

}
