<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\distribution\AccountSubscriptionManager;
use redhotmayo\registration\exceptions\ThrottleException;
use redhotmayo\registration\Registration;
use redhotmayo\registration\RegistrationValidator;
use redhotmayo\validation\ValidationException;

class RegistrationController extends  BaseController {
    /** @var redhotmayo\registration\RegistrationValidator $validator*/
    private $validator;

    /** @var UserRepository $userRepo */
    private $userRepo;

    /** @var Registration $registrationService */
    private $registrationService;

    /** @var \redhotmayo\distribution\AccountSubscriptionManager $subscriptionManager */
    private $subscriptionManager;

    public function __construct(Registration $registration, RegistrationValidator $validator, UserRepository $userRepo, AccountSubscriptionManager $subscriptionManager) {
        $this->validator = $validator;
        $this->userRepo = $userRepo;
        $this->registrationService = $registration;
        $this->subscriptionManager = $subscriptionManager;
    }

    public function index() {
        return View::make('registration.index');
    }

    public function store() {
        $input = Input::all();

        try {
            // register the user
            $status = $this->registrationService->register($input, $this->validator);

            // if registration failed due to not being able to save the user to database, alert user
            if (!$status) {
                Log::info("Registration service failure with status of false");
                Log::info($input);
                return $this->redirectWithErrors("Registration service failure");
//                return Redirect::back()->withErrors("Unable to register at this time... please try again soon");
            }

            // Login the user
            Auth::attempt(['username' => $input['username'], 'password' => $input['password']]);
            $data = Session::get(Cookie::get('temp_id'));
            $user = $this->getAuthedUser($data);

            $this->subscriptionManager->process($user, $data);

            //todo: Redirect to payment page here instead of profile page
            $contents = [
                'message' => 'Success',
                'redirect' => 'profile'
            ];

            $status = Response::HTTP_OK;

            $response = new Response();
            $response->setStatusCode($status);
            $response->setContent($contents);

            return $response;

        } catch (ValidationException $validationException) {
            Log::info("Registration Failure with Validation Exception");
            Log::info($input);
            return $this->redirectWithErrors($validationException->getErrors());
//            return Redirect::back()->withErrors($validationException->getErrors());
        } catch (ThrottleException $throttle) {
            return $this->redirectThrottled();
        } catch (Exception $e) {
            Log::info("Registration Failure with Exception");
            Log::info($input);
            return $this->redirectWithErrors($e->getMessage());
//            return Redirect::back()->withErrors($e->getMessage());
        }
    }

    private function redirectThrottled() {
        return $this->redirectWithErrors('Registration is currently closed at this moment');
    }

    private function redirectWithErrors($messages) {
        $contents = [
            'message' => $messages,
            'redirect' => 'reigstration'
        ];

        $status = Response::HTTP_LOCKED;

        $response = new Response();
        $response->setStatusCode($status);
        $response->setContent($contents);

        return $response;
    }

    /**
     * Get the authenticated user. If there is no currently authenticated user, null is returned.
     *
     * @return User|null
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function getAuthedUser() {
        $user = null;

        if (Auth::user()) {
            $user = $this->userRepository->find(['username' => Auth::user()->username]);
        }

        return $user;
    }
}
