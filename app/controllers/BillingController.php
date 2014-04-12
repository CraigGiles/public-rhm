<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use redhotmayo\billing\BillingManager;
use redhotmayo\billing\exception\BillingException;
use redhotmayo\model\Billing;

class BillingController extends BaseController {
    const BILLING_TOKEN = 'stripeToken';

    private $billingManager;

    function __construct(BillingManager $billingManager) {
        $this->billingManager = $billingManager;
    }

    public function index() {
        return View::make('billing.index');
    }

    public function store() {
        try {
            $token = $this->getBillingToken();
            $user = Auth::user();

            $this->billingManager->addBasicSubscription($user, $token);
        } catch (BillingException $billException) {
            Log::error("BillingException: {$billException->getMessage()}");
            $this->redirectWithErrors($billException->getErrors());
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
