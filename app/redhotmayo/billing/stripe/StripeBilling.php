<?php  namespace redhotmayo\billing\stripe;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use redhotmayo\billing\Billable;
use redhotmayo\billing\BillingInterface;
use redhotmayo\billing\exception\BillingException;
use redhotmayo\billing\exception\CardErrorException;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\dataaccess\repository\BillingRepository;
use redhotmayo\model\Billing;
use redhotmayo\model\User;
use redhotmayo\utility\Arrays;
use Stripe;


class StripeBilling extends Stripe implements BillingInterface {
    const PRIVATE_API_KEY = 'stripe.private_key';

    /** @var \redhotmayo\billing\Billable $billable */
    private $billable;

    /** @var \redhotmayo\dataaccess\repository\BillingRepository $billingRepo */
    private $billingRepo;

    /** @var \Stripe_Customer $customer */
    private $customer;

    public function __construct(BillingRepository $billingRepository, StripeCustomer $customer=null) {
        $this->billingRepo = $billingRepository;
        $this->customer = $customer;

        $this->setApiKey(Config::get(self::PRIVATE_API_KEY));
    }

    /**
     * Create a customer with the billing service given the current data
     *
     * @param \redhotmayo\model\User $user
     * @param $billableToken
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function createCustomer(User $user, $billableToken) {
        $customer = StripeCustomer::create([
            'card' => $billableToken,
            'description' => $user->getUsername(),
        ], $this->getApiKey());

        if (isset($customer) && $customer instanceof \Stripe_Customer) {
            return $customer->id;
        }

        return null;
    }

    /**
     * Subscribe a user to a billing plan
     *
     * @param BillingPlan $plan
     * @throws \redhotmayo\billing\exception\CardErrorException
     * @throws \redhotmayo\billing\exception\BillingException
     * @internal param \Stripe_Customer $customer
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function subscribe(BillingPlan $plan) {
        try {
            $customer = $this->getStripeCustomer();

            /** @var \Stripe_Subscription $result */
            $result = $customer->subscriptions->create([
                "plan" => $plan->getId()
            ]);

            $billing = Billing::createWithData([
                Billing::USER_ID => $this->billable->getUserId(),
                Billing::CUSTOMER_TOKEN => $this->billable->getBillingToken(),
                Billing::PLAN_ID => $plan->getId(),
                Billing::SUBSCRIPTION_ENDS_AT => Carbon::createFromTimestamp($result->current_period_end)->toDateTimeString(),
            ]);

            $this->billingRepo->save($billing);
        } catch (\Stripe_CardError $cardError) {
            $body = $cardError->getJsonBody();
            $message = Arrays::GetValue($body, 'message', '');
            Log::error($message);
            throw new CardErrorException($message);
        } catch (\Stripe_ApiConnectionError $apiConnectionError) {
            $body = $apiConnectionError->getJsonBody();
            $message = Arrays::GetValue($body, 'message', '');
            Log::error($message);
            throw new BillingException('Network connection with billing partner failed.');
        } catch (\Stripe_Error $stripeError) {
            $body = $stripeError->getJsonBody();
            $message = Arrays::GetValue($body, 'message', '');
            Log::error($message);
            throw new BillingException('Error will billing. Please try again later.');
        } catch (\Exception $ex) {
            Log::error($ex);
            throw new BillingException($ex);
        }
    }

    /**
     * Change the users current billing plan for a new billing plan
     *
     * @param BillingPlan $plan
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function change(BillingPlan $plan) {

    }

    /**
     * Cancel the current billing plan
     *
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function cancel() {
    }

    /**
     * Return the users current billing plan
     *
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getBillingPlan() {
    }

    /**
     * Return true if the user has an active subscription, false otherwise
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function isSubscribed() {
    }

    /**
     * @return StripeCustomer
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function createStripeCustomer() {
        return StripeCustomer::create([
            'card' => $customerToken,
            'description' => $this->billable->getEmail(),
        ], $this->getApiKey());
    }

    /**
     * @return StripeCustomer
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function getStripeCustomer() {
        if (!isset($this->customer)) {
            $id = $this->billable->getBillingToken();
            $this->customer = StripeCustomer::retrieve($id);
        }

        if (!isset($this->customer)) {
            $this->customer = $this->createStripeCustomer();
        }

        return $this->customer;
    }
}
