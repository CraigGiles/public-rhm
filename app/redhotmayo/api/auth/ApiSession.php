<?php namespace redhotmayo\api\auth;

use Illuminate\Support\Facades\DB;
use redhotmayo\model\User;

class ApiSession {
    const C_TOKEN = 'token';
    const C_USER = 'userId';
    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    public function create(User $user) {
        //generate a new API key
        $key = str_random(40);

        //save user session data with api key
        $id = DB::table('api_sessions')
            ->insertGetId(
                [
                    self::C_USER => $user->getUserId(),
                    self::C_TOKEN => $key,
                    self::C_CREATED_AT => date('Y-m-d H:i:s'),
                    self::C_UPDATED_AT => date('Y-m-d H:i:s'),
                ]
            );

        return $key;
    }
} 