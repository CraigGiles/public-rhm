<?php  namespace redhotmayo\billing\stripe;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use redhotmayo\billing\Billable;
use redhotmayo\billing\BillingInterface;
use redhotmayo\billing\exception\BillingException;
use redhotmayo\billing\exception\CardErrorException;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\dataaccess\repository\BillingRepository;
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

    public function __construct(BillingRepository $billingRepository, Billable $billable, StripeCustomer $customer=null) {
        $this->billable = $billable;
        $this->billingRepo = $billingRepository;
        $this->customer = $customer;

        $this->setApiKey(Config::get(self::PRIVATE_API_KEY));
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
    public function subcribe(BillingPlan $plan) {
        try {
            $customer = $this->getStripeCustomer();

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
        } catch (\Stripe_ApiConnectionError $apiConnectionError) {
            // Network communication with Stripe failed
            throw new BillingException('');
        } catch (\Stripe_Error $stripeError) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new BillingException('');
        } catch (\Exception $ex) {
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
     * @return StripeCustomer
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function createStripeCustomer() {
        return StripeCustomer::create([
            'card' => $this->billable->getId(),
            'description' => $this->billable->getEmail(),
        ], $this->getApiKey());
    }

    /**
     * @return StripeCustomer
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function getStripeCustomer() {
        $id = $this->billable->getId();
        $this->customer = StripeCustomer::retrieve($id);

        if (!isset($this->customer)) {
            $this->customer = $this->createStripeCustomer();
        }

        return $this->customer;
    }
}
