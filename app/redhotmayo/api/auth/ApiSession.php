<?php namespace redhotmayo\api\auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use redhotmayo\api\auth\exceptions\InvalidSessionException;
use redhotmayo\model\User;

class ApiSession {
    const TABLE_NAME = 'api_sessions';

    const C_TOKEN = 'token';
    const C_USER = 'userId';
    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    public function getSessionInformationForUser(User $user) {
        $id = (array)DB::table(self::TABLE_NAME)
                ->where(self::C_USER, '=', $user->getUserId())
                ->first();

        return isset($id) ? $id : false;
    }

    public function create(User $user) {
        //generate a new API key
        $key = str_random(40);

        //save user session data with api key
        $id = DB::table(self::TABLE_NAME)
            ->insertGetId(
                [
                    self::C_USER => $user->getUserId(),
                    self::C_TOKEN => $key,
                    self::C_CREATED_AT => Carbon::Now(),
                    self::C_UPDATED_AT => Carbon::Now(),
                ]
            );

        return $key;
    }

    public function getIdOfAuthedUser($token) {
        $id = null;

        if (isset($token)) {
            $userId = (array)DB::table(self::TABLE_NAME)
                        ->select(self::C_USER)
                        ->where(self::C_TOKEN, '=', $token)
                        ->first();

            if (isset($userId[self::C_USER])) {
                $id = $userId[self::C_USER];
            } else {
                throw new InvalidSessionException();
            }
        }

        return $id;
    }
} 
