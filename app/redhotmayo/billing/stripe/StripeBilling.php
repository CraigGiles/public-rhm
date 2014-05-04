<?php  namespace redhotmayo\billing\stripe;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use redhotmayo\billing\Billable;
use redhotmayo\billing\BillingInterface;
use redhotmayo\billing\exception\AuthenticationException;
use redhotmayo\billing\exception\BillingException;
use redhotmayo\billing\exception\CardErrorException;
use redhotmayo\billing\exception\InvalidRequestException;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\dataaccess\repository\BillingRepository;
use redhotmayo\exception\Exception;
use redhotmayo\model\Billing;
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

    public function __construct(BillingRepository $billingRepository, Billable $billable) {
        $this->billable = $billable;
        $this->billingRepo = $billingRepository;

        $this->setApiKey(Config::get(self::PRIVATE_API_KEY));
    }

    /**
     * Subscribe a user to a billing plan
     *
     * @param BillingPlan $plan
     *
     * @throws \redhotmayo\billing\exception\CardErrorException
     * @throws \redhotmayo\billing\exception\AuthenticationException
     * @throws \redhotmayo\billing\exception\BillingException
     * @throws \redhotmayo\billing\exception\InvalidRequestException
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function subcribe(BillingPlan $plan) {
        $customer = $this->getStripeCustomer();

        try {
            /** @var \Stripe_Subscription $result */
            $result = $customer->subscriptions->create([
                "plan" => $plan->getId()
            ]);

            $stripeId = $this->billable->getId();
            $stripeActive = true;
            $stripePlan = $plan->getId();
            $lastFour = $this->billable->getLastFour();
            $currentPeriodEnd = Carbon::createFromTimestamp($result->current_period_end)->toDateTimeString();
            $trialEndsAt = isset($result->trial_end) ? Carbon::createFromTimestamp($result->trial_end)->toDateTimeString() : null;

            $billing = Billing::createWithData([
                Billing::BILLABLE_ID => $stripeId,
                Billing::ACTIVE => (bool)$stripeActive,
                Billing::PLAN => $stripePlan,
                Billing::LAST_FOUR => $lastFour,
                Billing::CURRENT_PERIOD_END => $currentPeriodEnd,
                Billing::TRIAL_ENDS_AT => $trialEndsAt,
            ]);

            $this->billingRepo->save($billing);
        } catch (\Stripe_CardError $cardError) {
            $body = $cardError->getJsonBody();
            $message = Arrays::GetValue($body, 'message', '');
            throw new CardErrorException($message);
        } catch (\Stripe_InvalidRequestError $invalidRequestError) {
            // Invalid parameters were supplied to Stripe's API
            throw new InvalidRequestException('');
        } catch (\Stripe_AuthenticationError $authenticationError) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            throw new AuthenticationException('');
        } catch (\Stripe_ApiConnectionError $apiConnectionError) {
            // Network communication with Stripe failed
            throw new BillingException('');
        } catch (\Stripe_Error $stripeError) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new BillingException('');
        } catch (\Exception $ex) {
            dd($ex);
            // Something else happened, completely unrelated to Stripe
            throw new BillingException($ex->getMessage());
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
     * @return \Stripe_Customer
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function createStripeCustomer() {
        return \Stripe_Customer::create([
            'card' => $this->billable->getBillingToken(),
            'description' => $this->billable->getEmail(),
        ], $this->getApiKey());
    }

    private function getStripeCustomer() {
        $id = $this->billable->getId();
        $this->customer = \Stripe_Customer::retrieve($id);

        if (!isset($this->customer)) {
            $this->customer = $this->createStripeCustomer();
        }

        return $this->customer;
    }


}
