<?php  namespace redhotmayo\billing;

use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\model\User;

interface BillingService {
    public function subscribe(User $user, BillingPlan $plan, $token);

    //TODO implement these
//    public function change();
//    public function cancel();
}
