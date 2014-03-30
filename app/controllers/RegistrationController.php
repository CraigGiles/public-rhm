<?php

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
            $status = $this->registrationService->register($input, $this->validator);
            if (!$status) {
                return Redirect::back()->withErrors("Unable to register at this time... please try again soon");
            }

            $user = $this->userRepo->find(['username' => $input['username']]);
            //todo: Redirect to payment page here instead of just returning "registered"
            return "user has been registered. Please proceed to download the application from the Google Play Store.";

        } catch (ValidationException $validationException) {
            return Redirect::back()->withErrors($validationException->getErrors());
        } catch (Exception $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }
}