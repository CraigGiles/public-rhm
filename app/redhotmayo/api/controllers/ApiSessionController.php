<?php namespace redhotmayo\api\controllers;

use BaseController;
use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\api\auth\ApiSession;
use redhotmayo\api\auth\exceptions\LoginException;
use redhotmayo\auth\AuthorizationService;
use redhotmayo\model\User;

class ApiSessionController extends BaseController {
    const LOGIN = 'redhotmayo\api\controllers\ApiSessionController@login';
    const STATUS = 'status';
    const TOKEN = 'token';
    const MESSAGE = 'message';

    /** @var \redhotmayo\auth\AuthorizationService $auth */
    private $auth;

    /** @var \redhotmayo\api\auth\ApiSession $session */
    private $session;

    public function __construct(AuthorizationService $auth, ApiSession $session) {
        $this->auth = $auth;
        $this->session = $session;
    }

    public function login() {
        $array = array();

        try {
            $user = $this->auth->login(Input::json()->all());

            if ($user instanceof User) {
                $info = $this->session->getSessionInformationForUser($user);

                if (empty($info)) {
                    $array[self::TOKEN] = $this->session->create($user);
                } else {
                    $array[self::TOKEN] = $info->token;
                }
            } else {
                throw new LoginException('Invalid username or password.');
            }

            $success = true;
        } catch (Exception $e) {
            $success = false;
            $array[self::MESSAGE] = $e->getMessage();
        }

        $array[self::STATUS] = $success;

        return $array;
    }

    public function store() {
    }

    public function destroy() {
    }
}
