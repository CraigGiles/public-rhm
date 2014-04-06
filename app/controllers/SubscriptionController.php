<?php

use Illuminate\Support\Facades\View;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\UserRepository;

class SubscriptionController extends BaseController {

    /** @var \redhotmayo\dataaccess\repository\SubscriptionRepository $subscriptionRepository */
    private $subscriptionRepository;

    /** @var \redhotmayo\dataaccess\repository\UserRepository $userRepository */
    private $userRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository, UserRepository $userRepository) {
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
        //todo: Grab the input from the page and subscribe the user
    }
} 