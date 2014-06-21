<?php  namespace redhotmayo\billing;

use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\model\User;

interface BillingService {
    public function getUsersSubscription(User $user);
    public function subscribe(User $user);
    public function setBillingToken($token);

    //TODO implement these
//    public function change();
//    public function cancel();
}
