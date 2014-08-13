<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use redhotmayo\billing\BillingService;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\billing\exception\BillingException;
use redhotmayo\billing\Subscription;

class BillingController extends RedHotMayoWebController {
    const BILLING_TOKEN = 'stripeToken';

    private $billingService;

    function __construct(BillingService $billingService) {
        $this->billingService = $billingService;
    }

    public function index() {
        $user = $this->getAuthedUser();
        $showCurrentSubscription = false;

        /** @var Subscription $currentSub */
        $currentSub  = $this->billingService->getSubscriptionForUser($user);
        $currentPlan = BillingPlan::CreateFromId($currentSub->getPlanId());

        if ($currentPlan->getId() != '00') {
            $showCurrentSubscription = true;
        }

        $plan        = $this->billingService->createBillingPlanForUser($this->getAuthedUser());
        $name        = "redhotMAYO";
        $description = "Subscription Total";
        $image       = "assets/peppers.png";

        $params = [
            'name'           => $name,
            'email'          => $user->getEmail(),
            'description'    => $description,
            'showCurrentSubscription' => $showCurrentSubscription,
            'currentPrice'   => $currentPlan->getPrice() / 100,
            'priceInDollars' => $plan->getPrice() / 100,
            'priceInPennies' => $plan->getPrice(),
            'image'          => $image,
            'billingToken'   => Config::get('stripe.public_key')
        ];

        return View::make('billing.index', $params);
    }

    public function store() {
        try {
            $user  = $this->getAuthedUser();
            $token = $this->getBillingToken();

            $this->billingService->setBillingToken($token);
            $this->billingService->subscribe($user);

            /** @var Subscription $sub $sub */
            $sub = $this->billingService->getSubscriptionForUser($user);

            $params = [
                'endDate' => $sub->getSubscriptionEndDate()->format('M d, Y'),
            ];

            return View::make('billing.receipt', $params);
        } catch (BillingException $billException) {
            Log::error("BillingException: {$billException->getMessage()}");
            return Redirect::back()->withErrors($billException->getErrors());
        } catch (\redhotmayo\exception\Exception $ex) {
            Log::error("Generic Exception: {$ex->getMessage()}");
            return Redirect::back()->withErrors($ex->getErrors());
        } catch (Exception $genericException) {
            Log::error("Generic Exception: {$genericException->getMessage()}");
            return Redirect::back()->withErrors($genericException->getMessage());
        }
    }

    public function cancel() {
        $user = $this->getAuthedUser();
        $this->billingService->cancel($user);
        
        /** @var Subscription $sub */
        $sub = $this->billingService->getSubscriptionForUser($user);

        $params = [
            'endDate' => $sub->getSubscriptionEndDate()->format('M d, Y')
        ];

        return View::make('billing.cancel', $params);
    }

    private function getBillingToken() {
        $token = Input::get(self::BILLING_TOKEN);

        if (!isset($token)) {
            Redirect::back()->withErrors('Billing token not found');
        }

        return $token;
    }
}
