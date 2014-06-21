<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use redhotmayo\billing\BillingService;
use redhotmayo\billing\exception\BillingException;

class BillingController extends RedHotMayoWebController {
    const BILLING_TOKEN = 'stripeToken';

    private $billingService;

    function __construct(BillingService $billingService) {
        $this->billingService = $billingService;
    }

    public function index() {
        $user  = $this->getAuthedUser();
        $this->billingService->getUsersSubscription($user);
        return View::make('billing.index');
    }

    public function store() {
        try {
            $user  = $this->getAuthedUser();
            $token = $this->getBillingToken();

            $this->billingService->setBillingToken($token);
            $this->billingService->subscribe($user);

            return "Billed... Change me!";
        } catch (BillingException $billException) {
            Log::error("BillingException: {$billException->getMessage()}");
            return $this->redirectWithErrors($billException->getErrors());
        } catch (\redhotmayo\exception\Exception $ex) {
            Log::error("Generic Exception: {$ex->getMessage()}");
            return $this->redirectWithErrors($ex->getErrors());
        } catch (Exception $genericException) {
            Log::error("Generic Exception: {$genericException->getMessage()}");
            return $this->redirectWithErrors($genericException->getMessage());
        }
    }

    private function getBillingToken() {
        $token = Input::get(self::BILLING_TOKEN);

        if (!isset($token)) {
            $this->redirectWithErrors('Billing Token Not Found');
        }

        return $token;
    }

    private function redirectWithErrors($message, $input = []) {
        return Redirect::action('BillingController@index')
                ->withInput($input)
                ->withErrors($message);
    }
}
