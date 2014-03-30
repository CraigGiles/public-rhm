<?php namespace redhotmayo\api\controllers;

use BaseController;
use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\api\auth\ApiSession;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\registration\MobileRegistrationValidator;
use redhotmayo\registration\Registration;
use redhotmayo\validation\ValidationException;

class ApiRegistrationController extends BaseController {
    const STORE = 'redhotmayo\api\controllers\ApiRegistrationController@store';

    /**  @var ApiSession $session  */
    private $session;

    /** @var Registration $registrationService */
    private $registrationService;

    public function __construct(
        Registration $registration, UserRepository $userRepo, MobileRegistrationValidator $validator, ApiSession $session) {
        $this->registrationService = $registration;
        $this->userRepo = $userRepo;
        $this->validator = $validator;
        $this->session = $session;
    }

    public function store() {
        $results = [];
        $input = Input::json()->all();
        try {
            $status = $this->registrationService->register($input, $this->validator);
        } catch (ValidationException $validationException) {
            $status = false;
            $results['message'] = $validationException->getErrors()->toArray();
        } catch (Exception $e) {
            $status = false;
            $results['message'] = $e->getMessage();
        }

        $results['status'] = $status;
        return $results;
    }

}