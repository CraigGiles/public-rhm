<?php

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use redhotmayo\billing\BillingService;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\distribution\exception\AccountSubscriptionException;
use redhotmayo\distribution\AccountSubscriptionManager;
use redhotmayo\model\Region;
use redhotmayo\model\User;

class SubscriptionController extends RedHotMayoWebController {
    const TEMP_ID = 'temp_id';
    const REGIONS = 'regions';
    const ONE_DAY = 1440;

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

    /**
     * If the user is currently logged in, grab a list of zipcodes already subscribed by
     * the user and send it to the view. If the user has not registered but they've gone
     * through the subscription process, use the data in session to populate the view.
     * If there is no data for the user just send them to the view
     *
     * @return Response
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function index() {
        $data = [];

        /** @var User $user */
        $user = $this->getAuthedUser();

        if (isset($user)) {
            $data = $this->subscriptionRepository->find(['userId' => $user->getUserId()]);
        } else if (Cookie::get(self::TEMP_ID)) {
            //the user has a temporary id which means they've picked up some subscription data.
            //pass that data back to the view
            $data = Session::get(self::TEMP_ID);
        }

        return View::make('subscriptions.index', ['subscriptions' => $data] );
    }

    /**
     * Grab the subscription data filled out by the user and determine where to go from here.
     * If the user is not registered then store the subscription information in session and
     * redirect back to the registration page. Otherwise, pass the subscription information
     * to the subscription manager.
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function store() {
        try {
            $data = Input::json(self::REGIONS);
            $user = $this->getAuthedUser();

            if (!isset($user)) {
                return $this->storeInfoAndRedirect($data);
            }

            $this->subscriptionManager->process($user, $data);
            return $this->respondSuccess('login/confirmation');
        } catch (AccountSubscriptionException $ex) {
            Log::error("AccountSubscriptionException: {$ex->getMessage()}");
            return $this->respondAccountSubscriptionException($ex);
        }
    }

    public function total() {
        try {
            $regions = new Collection();
            $regionsAsArray = Input::json(self::REGIONS);

            foreach ($regionsAsArray as $array) {
                $regions->push(Region::createWithData($array));
            }

            $price = $this->billingService->getProposedTotalForRegions($regions->toArray());
            return $this->respondWithTotal($price);
        } catch (Exception $ex) {
            Log::error("AccountSubscriptionException: {$ex->getMessage()}");
            return $this->respondWithUnknownError($ex->getMessage());
        }
    }

    private function respondAccountSubscriptionException(AccountSubscriptionException $ex) {
        $this->setStatusCode(Response::HTTP_CONFLICT);
        $this->setMessages($ex->getErrors());
        $this->setRedirect('subscription');

        return $this->respond();
    }

    private function respondWithTotal($total) {
        $this->setStatusCode(Response::HTTP_OK);
        $this->setMessages($total);

        return $this->respond();
    }

    /**
     * Store a unique ID in the users cookie and use that ID as an index in the session to
     * store the users subscription data. Once the subscription data is stored in session,
     * redirect the user to registration.
     *
     * @param array $data
     *
     * @return \Illuminate\Http\Response
     * @author Craig Giles < craig@gilesc.com >
     */
    private function storeInfoAndRedirect($data) {
        $tempId = str_random(255);
        $cookie = Cookie::make(self::TEMP_ID, $tempId, self::ONE_DAY);
        Session::put($tempId, $data);

        $contents = [
            'message' => 'Unauthorized Access.',
            'redirect' => 'registration'
        ];

        $status = Response::HTTP_UNAUTHORIZED;

        $response = new Response();
        $response->setStatusCode($status);
        $response->setContent($contents);
        $response->withCookie($cookie);

        return $response;
    }

    /**
     * auth filter applied before the execution of this function described in the routes file
     *
     * @return mixed
     */
    public function update() {
        /** @var User $user */
        $user = $this->getAuthedUser();
        $states = $this->zipcodeRepository->getAllStates();
        $counties = $this->zipcodeRepository->getAllCounties(['state' => array_values($states)[0]]);
        $subscriptionLocations = [];

        if (isset($user)) {
            $subscriptionLocations = $this->subscriptionRepository->find(['userId' => $user->getUserId()]);
        } else if (Cookie::get(self::TEMP_ID)) {
            //the user has a temporary id which means they've picked up some subscription data.
            //pass that data back to the view
            $subscriptionLocations = Session::get(self::TEMP_ID);
            $subscriptionLocations = is_array($subscriptionLocations) ? $subscriptionLocations : [];
        }

        $subscriptionLocations = $this->filterUnique($subscriptionLocations);

        return View::make('subscriptions.update', [
            'activeSubscriptions' => $subscriptionLocations, 'states' => $states, 'counties' => $counties
        ]);
    }

    public function region_item_partial() {
        $data = [
            'class' => 'region-item',
            'searchTerm' => 'Search Term Here',
            'buttonText' => '+',
            'buttonColor' => 'success',
            'regionType' => 'city',
            'regionItemAddOrRemove' => 'region-item-add',
            'state' => 'CA'
        ];

        return View::make('subscriptions.partials.region_item', $data);
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
