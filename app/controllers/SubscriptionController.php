<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\distribution\exception\RegionalSubscriptionException;
use redhotmayo\distribution\RegionalSubscriptionManager;
use redhotmayo\model\SubscriptionLocation;

class SubscriptionController extends BaseController {
    const TEMP_ID = 'temp_id';
    const DATA = 'regions';
    const ONE_DAY = 1440;

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

        try {
            $user = $this->getAuthedUser($data);
            $this->regSubManager->subscribeRegionsToUser($user, $data);
            Redirect::to('profile');
        } catch (RegionalSubscriptionException $ex) {
            Log::error("RegionalSubscriptionException: {$ex->getMessage()}");
            $this->redirectWithErrors($ex->getErrors());
        }
    }

    private function redirectWithErrors($messages, $input=[]) {
        Redirect::action('SubscriptionController@index')
                ->withInput($input)
                ->withErrors($messages);
    }

    private function getAuthedUser($data) {
        // If user is not registered, store their subscriptions and register them.
        if (Auth::user()) {
            return $this->userRepository->find(['username' => Auth::user()->username]);
        } else {
            $tempId = str_random(255);
            Cookie::make(self::TEMP_ID, $tempId, self::ONE_DAY);
            Session::put($tempId, $data);

            Redirect::to('registration');
        }
    }
}
