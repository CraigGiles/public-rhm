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
                return $this->respondWithUnknownError("Registration service failure");
            }

            // Login the user and process subscription data, then send them to confirmation page
            Auth::attempt(['username' => $input['username'], 'password' => $input['password']]);
            $user = $this->getAuthedUser();
            $this->subscriptionManager->processNewUsersData($user);

//            return View::make('registration.confirmation');
            $contents = [
                'message' => 'Registration Successful.',
                'redirect' => 'registration/confirmation'
            ];
            $status = Response::HTTP_OK;
            $response = new Response();
            $response->setStatusCode($status);
            $response->setContent($contents);
            return $response;
        } catch (ValidationException $validationException) {
            Log::info("Registration Failure due to validation exception. Token Used: " . $validationException->getToken());
            return $this->respondValidationException($validationException);
        } catch (ThrottleException $throttle) {
            Log::info("Registration failure due to throttle.");
            return $this->respondThrottled($throttle);
        } catch (Exception $e) {
            Log::error("Registration Failure with Exception.");
            Log::error($e);
            return $this->respondWithUnknownError('Error while registering. Please try again later.');
        }
    }

    public function confirmation() {
        return View::make('registration.confirmation');
    }

    private function respondThrottled(ThrottleException $throttle) {
        Log::info($throttle->getErrors());
        $this->setStatusCode(Response::HTTP_LOCKED);
        $this->setMessages($throttle->getMessage());
        $this->setRedirect('registration');

        return $this->respond();
    }

    private function respondValidationException(ValidationException $validationException) {
        $this->setStatusCode(Response::HTTP_CONFLICT);
        $this->setMessages($validationException->getErrors());
        $this->setRedirect('registration');

        return $this->respond();
    }
}
