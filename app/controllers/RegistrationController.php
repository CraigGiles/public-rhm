<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use redhotmayo\dataaccess\repository\UserRepository;
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

    public function __construct(Registration $registration, RegistrationValidator $validator, UserRepository $userRepo) {
        $this->validator = $validator;
        $this->userRepo = $userRepo;
        $this->registrationService = $registration;
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
                return Redirect::back()->withErrors("Unable to register at this time... please try again soon");
            }

            // Login the user
            Auth::attempt(['username' => $input['username'], 'password' => $input['password']]);

            //todo: Redirect to payment page here instead of profile page
            return Redirect::to('profile');

        } catch (ValidationException $validationException) {
            Log::info("Registration Failure with Validation Exception");
            Log::info($input);
            return Redirect::back()->withErrors($validationException->getErrors());
        } catch (Exception $e) {
            Log::info("Registration Failure with Exception");
            Log::info($input);
            return Redirect::back()->withErrors($e->getMessage());
        }
    }
}