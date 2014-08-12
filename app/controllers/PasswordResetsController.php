<?php

use Illuminate\Auth\Reminders\PasswordBroker;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use redhotmayo\dataaccess\repository\UserRepository;

class PasswordResetsController extends \BaseController {
    private $userRepo;

    public function __construct(UserRepository $repo) {
        $this->userRepo= $repo;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('password_resets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        Password::remind(['email' => Input::get('email')]);
        return View::make('password_resets.sent')->withSuccess(true);
    }

    public function resetPasswordForm($token) {
        return View::make('password_resets.reset')->withToken($token);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string $token
     * @return Response
     */
    public function update($token) {
        $creds = [
            'token' => $token,
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
        ];

        $something = Password::reset($creds, function ($user, $password) use ($creds) {
            $user->setPassword(Hash::make($password));
            $this->userRepo->save($user);

            return Redirect::route('sessions.create');
        });

        $message = 'Invalid Password';
        if ($something === PasswordBroker::INVALID_TOKEN) {
            $message = 'Your session expired. Please fill request another password reset';
            return Redirect::route('password_resets.create')->with('flash_message', $message);
        } else if ($something === PasswordBroker::INVALID_PASSWORD) {
            if (count($creds['password'] < 6)) { $message = 'Password must be at lesat 6 characters'; }
            return Redirect::back()->with('flash_message', $message)->withInput();
        }

        return Redirect::route('login')->with('flash_message', 'Your credentials have been updated');
    }
}