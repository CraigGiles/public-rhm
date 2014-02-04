<?php namespace redhotmayo\api\auth;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\dataaccess\repository\dao\sql\UserSQL;
use redhotmayo\model\User;

class ApiAuthorizer {
    public function login($input) {
        $input = json_decode($input[0]);

        $attempt = Auth::attempt([
            'username' => $input->username,
            'password' => $input->password
        ]);

        if ($attempt) {
            $dao = DataAccessObject::GetUserDAO();

            $credentials = [
                'username' => $input->username,
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
