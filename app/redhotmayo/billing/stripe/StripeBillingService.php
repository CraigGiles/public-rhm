<?php  namespace redhotmayo\billing\stripe;

use Illuminate\Support\Facades\Config;
use redhotmayo\billing\BillingService;
use redhotmayo\dataaccess\repository\BillingRepository;

class StripeBillingService implements BillingService {
    const PRIVATE_API_KEY = 'stripe.private_key';
    const UNKNOWN_CUSTOMER_TOKEN = '';

    public function __construct(BillingRepository $billingRepository) {
        $this->billingRepo = $billingRepository;
        $this->setApiKey(Config::get(self::PRIVATE_API_KEY));
    }
}

