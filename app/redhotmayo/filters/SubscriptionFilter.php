<?php  namespace redhotmayo\filters;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use redhotmayo\billing\Subscription;
use redhotmayo\dataaccess\repository\BillingRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\model\User;

class SubscriptionFilter {
    const MESSAGE = 'You must have an active subscription to access this feature.';

    /**
     * Return true if the user is authorized and has an active subscription
     * false otherwise
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    protected function isActive() {
        $active = false;

        if (Auth::user()) {
            /** @var UserRepository $userRepo */
            $userRepo = App::make('UserRepository');

            /** @var BillingRepository $billingRepo */
            $billingRepo = App::make('BillingRepository');

            /** @var User $user */
            $user = $userRepo->find(['username' => Auth::user()->username]);

            /** @var Subscription $sub */
            $sub = $billingRepo->getSubscriptionForUser($user);

            if ($sub->isActive()) {
                $active = true;
            }
        }

        return $active;
    }
}
