<?php namespace redhotmayo\api\controllers;

use BaseController;
use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\api\auth\ApiSession;
use redhotmayo\dataaccess\repository\RepositoryFactory;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\facade\Registration;
use redhotmayo\model\User;
use redhotmayo\registration\exceptions\UserExistsException;
use redhotmayo\registration\MobileRegistrationValidator;
use redhotmayo\validation\ValidationException;

class ApiRegistrationController extends BaseController {
    const STORE = 'redhotmayo\api\controllers\ApiRegistrationController@store';

    /**  @var ApiSession $session  */
    private $session;

    public function __construct(UserRepository $userRepo, MobileRegistrationValidator $validator, ApiSession $session) {
        $this->userRepo = $userRepo;
        $this->validator = $validator;
        $this->session = $session;
    }

    public function store() {
        $results = [];
        $input = Input::json()->all();

        try {
            $status = Registration::mobileRegistration($input, $this->validator);
            $user = $this->userRepo->find(['username' => $input['username']]);
//            $results['token'] = $this->session->create($user);
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