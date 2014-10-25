<?php namespace redhotmayo\api\auth;

use Illuminate\Support\Facades\DB;
use redhotmayo\auth\AuthorizationService;

/**
 * TODO: This class will be moving over to the AuthorizationService.
 *
 * @package redhotmayo\api\auth
 * @author Craig Giles < craig@gilesc.com >
 * @deprecated
 */
class ApiAuthorizer {
    protected $message = [];

    /** @var \redhotmayo\auth\AuthorizationService $auth */
    protected $auth;

    public function __construct(AuthorizationService $auth) {
        $this->auth = $auth;
    }

    public function getMessage() {
        return $this->message;
    }

    public function authorize($token) {
        $authorized = false;

        //if user token is valid, let them go through.
        if (isset($token)) {
            $session = DB::table('api_sessions')
                    ->where('token', '=', $token)
                    ->first();

            //if session created_at is within the time limitations we set
            // todo: token should expire

            $authorized = empty($session) ? false : true;
        }

        //otherwise, return false and set message
        if (!$authorized && isset($token)) {
            $this->message = ['Token has expired or is invalid. Please log in.'];
        } else {
            $this->message = ['Token not found. Please log in to obtain an API token.'];
        }

        return $authorized;
    }
}
