<?php  namespace redhotmayo\billing;

use redhotmayo\model\User;

interface BillingService {
    public function getUsersSubscription(User $user);
    public function subscribe(User $user);
    public function cancel(User $user);
    public function setBillingToken($token);
}
