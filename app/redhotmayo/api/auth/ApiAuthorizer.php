<?php namespace redhotmayo\api\auth;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\dataaccess\repository\dao\sql\UserSQL;
use redhotmayo\model\User;

class ApiAuthorizer {
    protected $message = [];

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
            // ...

            $authorized = empty($session) ? false : true;
        }

        //otherwise, return false and set message
        if (!$authorized && isset($token)) {
            $this->message = ['Token has expired or is invalid. Please log in.'];
        } else {
            $this->message = ['Token not found. Please provide a valid token'];
        }

        return $authorized;
    }

    public function login($input) {

        $attempt = Auth::attempt($input);
        if ($attempt) {
            $dao = DataAccessObject::GetUserDAO();

            $credentials = [
                'username' => $input['username'],
            ];

            $result = $dao->getUser($credentials);
            $user = User::FromStdClass($result);

            $id = DB::table('api_sessions')
                    ->where('userId', '=', $user->getUserId())
                    ->get();

            if (empty($id)) {
                $api = new ApiSession();
                return $api->create($user);
            } else {
                return $id[0]->token;
            }

        }
    }
}
