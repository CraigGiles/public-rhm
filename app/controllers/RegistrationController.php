<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\distribution\AccountSubscriptionManager;
use redhotmayo\registration\exceptions\ThrottleException;
use redhotmayo\registration\Registration;
use redhotmayo\registration\RegistrationValidator;
use redhotmayo\validation\ValidationException;

class RegistrationController extends RedHotMayoWebController {
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
                $mb = new \Illuminate\Support\MessageBag($input);
                foreach ($mb->getMessages() as $msg) {
                    Log::info($msg);
                }
                return $this->respondWithUnknownError("Registration service failure");
            }

            // Login the user
            Auth::attempt(['username' => $input['username'], 'password' => $input['password']]);
            $user = $this->getAuthedUser();
            $this->subscriptionManager->processNewUsersData($user);

            return $this->respondSuccess('profile');
        } catch (ValidationException $validationException) {
            Log::info("Registration Failure with Validation Exception");
            return $this->respondValidationException($validationException);
        } catch (ThrottleException $throttle) {
            Log::info("Registration throttled");
            return $this->respondThrottled($throttle);
        } catch (Exception $e) {
            Log::error("Registration Failure with Exception");
            return $this->respondWithUnknownError($e->getMessage());
        }
    }

    private function respondThrottled(ThrottleException $throttle) {
        $this->setStatusCode(Response::HTTP_LOCKED);
        $this->setMessages($throttle->getErrors());
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
