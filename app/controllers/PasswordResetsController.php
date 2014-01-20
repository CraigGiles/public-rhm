<?php

use Illuminate\Auth\Reminders\DatabaseReminderRepository;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use redhotmayo\dataaccess\repository\RepositoryFactory;
use redhotmayo\dataaccess\repository\UserRepository;

class PasswordResetsController extends \BaseController {

    public function __construct(UserRepository $repo) {
        $this->userRepo= $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
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
        return Redirect::route('password_resets.create')->withSuccess(true);

        // find user with email address Input::get('email')
        // generate a random 32 char key and place it into the password_reminders table
        // email user with the key
    }

    public function resetPasswordForm($token) {
        return View::make('password_resets.reset')
                   ->withToken($token);
    }

    public function reset($token) {
        //verify token is valid (< 60 min old)
        $creds = [
            'token' => $token,
            'username' => 'testuser',
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
        ];

        return Password::reset($creds, function ($user, $password) use ($creds) {
            $user->setPassword(Hash::make($password));
            $repo = RepositoryFactory::GetUserRepository();
            $repo->save($user);

            return Redirect::route('sessions.create');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}