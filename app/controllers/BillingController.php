<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use redhotmayo\billing\BillingService;
use redhotmayo\billing\exception\BillingException;
use redhotmayo\billing\Subscription;

class BillingController extends RedHotMayoWebController {
    const BILLING_TOKEN = 'stripeToken';

    private $billingService;

    function __construct(BillingService $billingService) {
        $this->billingService = $billingService;
    }

    public function index() {
        $plan        = $this->billingService->createBillingPlanForUser($this->getAuthedUser());
        $population  = $this->billingService->getPopulationCountForUser($this->getAuthedUser());
        $name        = "Red Hot MAYO";
        $description = "Subscription Total";
        $image       = "128x128.png";

        $params = [
            'name'         => $name,
            'description'  => $description,
            'population'   => $population,
            'price'        => $plan->getPrice() / 100,
            'image'        => $image,
            'billingToken' => Config::get('stripe.public_key')
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
