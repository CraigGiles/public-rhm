<?php  namespace redhotmayo\billing\stripe;

use Carbon\Carbon;

class StripeSubscriptionMapper {
    public static function Map(\Stripe_Subscription $subscription) {
        $currentPeriodEnd = isset($subscription->current_period_end) ? Carbon::createFromTimestamp($subscription->current_period_end) : null;
        $trialEnd = isset($subscription->trial_end) ? Carbon::createFromTimestamp($subscription->trial_end) : null;
        $canceledAt = isset($subscription->canceled_at) ? Carbon::createFromTimestamp($subscription->canceled_at) : null;

        return new StripeSubscription([
            StripeSubscription::PLAN_ID                     => $subscription->plan->id,
            StripeSubscription::STRIPE_STATUS               => $subscription->status,
            StripeSubscription::STRIPE_CUSTOMER_TOKEN       => $subscription->customer,
            StripeSubscription::STRIPE_CANCEL_AT_PERIOD_END => $subscription->cancel_at_period_end,
            StripeSubscription::STRIPE_CURRENT_PERIOD_END   => $currentPeriodEnd,
            StripeSubscription::STRIPE_TRIAL_END            => $trialEnd,
            StripeSubscription::STRIPE_CANCELED_AT          => $canceledAt,
        ]);
    }
} 
