<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use redhotmayo\billing\BillingService;
use redhotmayo\billing\exception\BillingException;
use redhotmayo\billing\plan\BillingPlan;

class BillingController extends RedHotMayoWebController {
    const BILLING_TOKEN = 'stripeToken';

    private $billingService;

    function __construct(BillingService $billingService) {
        $this->billingService = $billingService;
    }

    public function index() {
        return View::make('billing.index');
    }

    public function store() {
        try {
            $user = $this->getAuthedUser();
            $token = $this->getBillingToken();

            $plan = BillingPlan::CreateFromId(BillingPlan::PREMIUM);

            $this->billingService->subscribe($user, $plan, $token);

            return "Billed... Change me!";
        } catch (BillingException $billException) {
            Log::error("BillingException: {$billException->getMessage()}");
            $this->redirectWithErrors($billException->getErrors());
        } catch (\redhotmayo\exception\Exception $ex) {
            Log::error("Generic Exception: {$ex->getMessage()}");
            $this->redirectWithErrors($ex->getErrors());
        }
    }

    private function getBillingToken() {
        $token = Input::get(self::BILLING_TOKEN);

        if (!isset($token)) {
            $this->redirectWithErrors('Billing Token Not Found');
        }

       return $token;
    }

    private function redirectWithErrors($message, $input=[]) {
        Redirect::action('BillingController@index')
                ->withInput($input)
                ->withErrors($message);
    }
}
