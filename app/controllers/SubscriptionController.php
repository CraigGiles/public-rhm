<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\distribution\exception\RegionalSubscriptionException;
use redhotmayo\distribution\RegionalSubscriptionManager;
use redhotmayo\model\SubscriptionLocation;

class SubscriptionController extends BaseController {
    const TEMP_ID = 'temp_id';
    const DATA = 'regions';

    /** @var \redhotmayo\dataaccess\repository\SubscriptionRepository $subscriptionRepository */
    private $subscriptionRepository;

    /** @var \redhotmayo\dataaccess\repository\UserRepository $userRepository */
    private $userRepository;

    /** @var \redhotmayo\distribution\RegionalSubscriptionManager $regSubManager */
    private $regSubManager;

    public function __construct(SubscriptionRepository $subscriptionRepository,
                                UserRepository $userRepository,
                                RegionalSubscriptionManager $regionalSubscriptionManager
    ) {
        $this->regSubManager = $regionalSubscriptionManager;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $data = [];
        //if the user is currently logged in, grab a list of zipcodes already subscribed
        //and send them along with the view.. otherwise just send the view.
        if (Auth::user()) {
            $data = $this->subscriptionRepository->find(['userId' => Auth::user()->id]);
        } else if (Cookie::get(self::TEMP_ID)) {
            //the user has a temporary id adn there-for has picked up some subscription data.
            //pass that data back to the view
            $data = Session::get(self::TEMP_ID);
        }

        //TODO: not sure how to pass the subscription location object to the front end. The basic data will look like the following:
        /**
         * array (size=1)
            0 =>
                object(redhotmayo\model\SubscriptionLocation)[253]
                private 'city' => string 'SAN FRANCISCO' (length=13)
                private 'county' => string 'SAN FRANCISCO' (length=13)
                private 'state' => string 'CA' (length=2)
                private 'zipcode' => int 94132
                private 'population' => int 28129
         */
        return View::make('subscriptions.index', ['subscriptions' => $data] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $userId = null;
        $data = Input::get(self::DATA);
        $data = Input::json(self::DATA);

        if (Auth::user()) {
            $userId = Auth::user()->id;
        }

//        foreach ($data as $subscription) {
//            $tmp = SubscriptionLocation::FromArray($subscription);
//            $tmp->setUserId($userId);
//        }

        // If user is not registered, store their subscriptions and register them.
        if (!Auth::user()) {
            $tempId = str_random(255);
            Cookie::put(self::TEMP_ID, $tempId);
            Session::put($tempId, $data);
            Redirect::to('registration');
        }

        try {
            $user = $this->userRepository->find(['username' => Auth::user()->username]);
            $this->regSubManager->subscribeRegionsToUser($user, $data);
        } catch (RegionalSubscriptionException $ex) {

        }

//        //todo: calculate subscription value here (newSub - currentSub)
//        //$subDifference = Billing->Calculate(userId, newSubs) ?
//
//        //todo: if new subDifference is more expensive than old subscription (IE: positive number)
//        //todo: save in session and redirect to billing
//        //Session::put(self::SUBSCRIPTION . Auth::user()->id, $subs);
//
//        //todo otherwise, the subscription hasn't changed based on new areas. Save and send them off.
//        $subs = [];
//        foreach ($data as $sub) {
//            $subLocation = SubscriptionLocation::FromArray($sub);
//            $subs[] = SubscriptionLocation::FromArray($sub);
//        }
//
//        $this->subscriptionRepository->saveAll($subs);
        Redirect::to('profile');
    }
}
