<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\distribution\AccountSubscriptionManager;
use redhotmayo\registration\exceptions\ThrottleException;
use redhotmayo\registration\Registration;
use redhotmayo\registration\RegistrationValidator;
use redhotmayo\validation\ValidationException;

class RegistrationController extends RedHotMayoWebController {
    /** @var redhotmayo\registration\RegistrationValidator $validator */
    private $validator;

    /** @var UserRepository $userRepo */
    private $userRepo;

    /** @var Registration $registrationService */
    private $registrationService;

    /** @var \redhotmayo\distribution\AccountSubscriptionManager $subscriptionManager */
    private $subscriptionManager;

    public function __construct(
        Registration $registration, RegistrationValidator $validator, UserRepository $userRepo,
        AccountSubscriptionManager $subscriptionManager
    ) {
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

                $mb = new \Illuminate\Support\MessageBag($input);
                foreach ($mb->getMessages() as $msg) {
                    Log::info($msg);
                }
                return Redirect::back()->withErrors("Registration service failure")->withInput();
            }

            // Login the user and process subscription data, then send them to confirmation page
            Auth::attempt(['username' => $input['username'], 'password' => $input['password']]);
            $user = $this->getAuthedUser();
            $this->subscriptionManager->processNewUsersData($user);

            return Redirect::to('billing');
        } catch (ValidationException $validationException) {
            Log::info("Registration Failure due to validation exception. ");
            return Redirect::back()->withErrors($validationException->getErrors())->withInput();
        } catch (ThrottleException $throttle) {
            Log::info("Registration failure due to throttle. Token Used: " . $throttle->getToken() );
            return Redirect::back()->withErrors($throttle->getErrors())->withInput();
        } catch (Exception $e) {
            Log::error("Registration Failure with Exception.");
            Log::error($e);
            return Redirect::back()->withErrors($e->getErrors())->withInput();
        }
    }

    public function confirmation() {
        return View::make('registration.confirmation');
    }
}
