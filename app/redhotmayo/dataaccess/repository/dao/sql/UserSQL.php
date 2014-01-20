<?php namespace redhotmayo\dataaccess\repository\dao\sql;


use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\dao\UserDAO;
use redhotmayo\model\User;

class UserSQL implements UserDAO {
    const TABLE_NAME = 'users';
    const C_ID = 'id';
    const C_USER_NAME = 'username';
    const C_PASSWORD = 'password';
    const C_EMAIL = 'email';
    const C_EMAIL_VERIFIED = 'emailVerified';
    const C_PERMISSIONS = 'permissions';
    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    public static function GetColumns() {
        return [
            self::TABLE_NAME .'.'. self::C_ID,
            self::TABLE_NAME .'.'. self::C_USER_NAME,
            self::TABLE_NAME .'.'. self::C_PASSWORD,
            self::TABLE_NAME .'.'. self::C_EMAIL,
            self::TABLE_NAME .'.'. self::C_EMAIL_VERIFIED,
            self::TABLE_NAME .'.'. self::C_PERMISSIONS,
            self::TABLE_NAME .'.'. self::C_ACTIVATED,
            self::TABLE_NAME .'.'. self::C_ACTIVATION_CODE,
            self::TABLE_NAME .'.'. self::C_ACTIVATED_AT,
            self::TABLE_NAME .'.'. self::C_LAST_LOGIN,
            self::TABLE_NAME .'.'. self::C_PERSIST_CODE,
            self::TABLE_NAME .'.'. self::C_RESET_PASSWORD_CODE,
            self::TABLE_NAME .'.'. self::C_CREATED_AT,
            self::TABLE_NAME .'.'. self::C_UPDATED_AT,
        ];
    }
    /**
     * Save a record and return the objectId
     *
     * @param User $user
     * @return mixed
     */
    public function save(User $user) {
        $userid = $user->getUserId();

        if (isset($userid)) {
            return $this->update($user);
        } else {
            $id = DB::table('users')
                    ->insertGetId([
                    self::C_USER_NAME => $user->getUsername(),
                    self::C_PASSWORD => $user->getPassword(),
                    self::C_EMAIL => $user->getEmail(),
                    self::C_EMAIL_VERIFIED => $user->getEmailVerified(),
                    self::C_PERMISSIONS => $user->getPermissions(),
                    self::C_CREATED_AT => date('Y-m-d H:i:s'),
                    self::C_UPDATED_AT => date('Y-m-d H:i:s'),
                ]);

            $user->setUserId($id);
            return $id;
        }
    }

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $users
     * @return array
     */
    public function saveAll(array $users) {
        // TODO: Implement saveAll() method.
    }

    /**
     * Obtains a user record based on the credentials
     *
     * @param $credentials
     * @return mixed
     */
    public function getUser($credentials) {
        $user = DB::table('users')
            ->where('email', '=', $credentials['email'])
            ->get();

        if (isset($user) && count($user) === 1) return $user[0];
        else return null;
    }

    private function update(User $user) {
        $id = $user->getUserId();
        $values = $this->getValues($user, isset($id));
        DB::table('users')
          ->where(self::C_ID, $id)
          ->update($values);
    }

    private function getValues(User $user, $updating=false) {
        $emailVerified = (bool)$user->getEmailVerified();

        $values = [
            self::C_USER_NAME => $user->getUsername(),
            self::C_PASSWORD => $user->getPassword(),
            self::C_EMAIL => $user->getEmail(),
            self::C_EMAIL_VERIFIED => $emailVerified,
            self::C_PERMISSIONS => $user->getPermissions(),
            self::C_UPDATED_AT => date('Y-m-d H:i:s'),
        ];

        if (!$updating) {
            $values[self::C_CREATED_AT] = date('Y-m-d H:i:s');
        }

        return $values;
    }
}