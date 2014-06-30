<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use redhotmayo\billing\BillingService;
use redhotmayo\billing\exception\BillingException;

class BillingController extends RedHotMayoWebController {
    const BILLING_TOKEN = 'stripeToken';

    private $billingService;

    function __construct(BillingService $billingService) {
        $this->billingService = $billingService;
    }

    public function index() {
        $amount      = "9000";
        $name        = "RedHotMAYO";
        $description = "Subscription Total";
        $image       = "128x128.png";

        $params = [
            'name' => $name,
            'description' => $description,
            'amount' => $amount,
            'image' => $image,
        ];

        return View::make('billing.index', $params);
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
        return "Canceled...";
    }

    private function getBillingToken() {
        $token = Input::get(self::BILLING_TOKEN);

        if (!isset($token)) {
            Redirect::back()->withErrors('Billing token not found');
        }

        return $token;
    }
}
