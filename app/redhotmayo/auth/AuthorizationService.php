<?php  namespace redhotmayo\auth;

use Illuminate\Support\Facades\Auth;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\utility\Arrays;

class AuthorizationService {
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const SERVICE = '\redhotmayo\auth\AuthorizationService';

    /** @var \redhotmayo\dataaccess\repository\UserRepository $userRepo */
    private $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function login(array $input) {
        $username = Arrays::GetValue($input, self::USERNAME, '');
        $password = Arrays::GetValue($input, self::PASSWORD, '');
        $user = null;

        $attempt = Auth::attempt([
            self::USERNAME => $username,
            self::PASSWORD => $password
        ]);

        if ($attempt) {
            $user = $this->userRepo->find([self::USERNAME => $username]);
        }

        return isset($user) ? $user : false;
    }
}
