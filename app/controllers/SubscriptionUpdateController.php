<?php

use redhotmayo\billing\BillingService;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\distribution\AccountSubscriptionManager;

class SubscriptionUpdateController extends RedHotMayoWebController {
    /** @var \redhotmayo\dataaccess\repository\SubscriptionRepository $subscriptionRepository */
    private $subscriptionRepository;

    /** @var \redhotmayo\dataaccess\repository\UserRepository $userRepository */
    private $userRepository;

    /** @var \redhotmayo\distribution\AccountSubscriptionManager $subscriptionManager */
    private $subscriptionManager;

    /** @var \redhotmayo\dataaccess\repository\ZipcodeRepository $zipcodeRepository */
    private $zipcodeRepository;

    /** @var \redhotmayo\billing\BillingService $billingService */
    private $billingService;

    public function __construct(SubscriptionRepository $subscriptionRepository,
                                UserRepository $userRepository,
                                ZipcodeRepository $zipcodeRepository,
                                AccountSubscriptionManager $subscriptionManager,
                                BillingService $billingService
    ) {
        $this->subscriptionManager = $subscriptionManager;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userRepository = $userRepository;
        $this->zipcodeRepository = $zipcodeRepository;
        $this->billingService = $billingService;
    }

    public function update() {
        $states = $this->zipcodeRepository->getAllStates();
        $counties = $this->zipcodeRepository->getAllCounties(['state' => 'CA']);

        $user = $this->getAuthedUser();
        $data = $this->subscriptionRepository->find(['userId' => $user->getUserId()]);
        $data = $this->filterUnique($data);

        $activeState = null;
        $activeCounty = null;

        $sidebar = [
            'states' => $states,
            'counties' => $counties,
            'state' => $activeState,
            'county' => $activeCounty,
        ];

        return View::make('subscriptions.v2.index', ['sidebar' => $sidebar, 'subscriptions' => $data, 'states' => $states, 'counties' => []]);
    }

    public function posted() {
        dd(Input::all());
    }

    private function filterUnique(array $data) {
        $cities = [];

        /** @var Region $d */
        foreach ($data as $d) {
            $cities[$d->getCity()] = $d;
        }

        return array_values($cities);
    }
}
